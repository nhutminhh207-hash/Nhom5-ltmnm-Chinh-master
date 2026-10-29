<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\{User, Product, ProductVariant, CartItem, Order};

class OrderService
{
    public const TRANSITIONS = [
        'pending'   => ['confirmed', 'cancelled'],
        'confirmed' => ['shipping', 'cancelled'],
        'shipping'  => ['completed'],
        'completed' => [],
        'cancelled' => []
    ];

    private function fail($message)
    {
        throw ValidationException::withMessages(['order' => $message]);
    }

    public function checkout(User $user, array $delivery, string $token, array $quote, string $paymentMethod = 'cod'): Order
    {
        return DB::transaction(function () use ($user, $delivery, $token, $quote, $paymentMethod) {
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();

            if ($existing = Order::where('checkout_token', $token)->first()) {
                abort_unless($existing->user_id === $user->id, 403);
                return $existing;
            }

            $cart = CartItem::where('user_id', $user->id)->orderBy('variant_id')->lockForUpdate()->get();
            if ($cart->isEmpty()) {
                $this->fail('Giỏ hàng trống.');
            }

            $variants = ProductVariant::whereIn('id', $cart->pluck('variant_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $products = Product::whereIn('id', $variants->pluck('product_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            $total = 0;
            $lines = [];

            foreach ($cart as $item) {
                $v = $variants->get($item->variant_id);
                $p = $v ? $products->get($v->product_id) : null;

                if (!$v || !$p || !$v->is_active || !$p->is_active || $item->quantity < 1 || $item->quantity > $v->stock) {
                    $this->fail('Sản phẩm đã ẩn hoặc không đủ tồn kho.');
                }

                if (!isset($quote[$v->id]) || (int)$quote[$v->id]['price'] !== (int)$v->price || (int)$quote[$v->id]['quantity'] !== $item->quantity || count($quote) !== $cart->count()) {
                    $this->fail('Giá hoặc giỏ hàng đã thay đổi. Mở lại trang thanh toán để xác nhận.');
                }

                // Sửa lại đoạn tính giá chính xác:
                $line = $v->price * $item->quantity;
                $total += $line;

                $lines[] = [
                    'variant_id'   => $v->id,
                    'product_name' => $p->name,
                    'sku'          => $v->sku,
                    'size'         => $v->size,
                    'color'        => $v->color,
                    'unit_price'   => $v->price,
                    'quantity'     => $item->quantity,
                    'line_total'   => $line
                ];
            }

            $orderData = array_merge($delivery, [
                'user_id'        => $user->id,
                'checkout_token' => $token,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'status'         => 'pending',
                'total_amount'   => $total
            ]);

            $order = Order::create($orderData);
            $order->items()->createMany($lines);

            foreach ($lines as $line) {
                $variants[$line['variant_id']]->decrement('stock', $line['quantity']);
            }

            CartItem::whereIn('id', $cart->pluck('id'))->delete();

            return $order;
        }, 3);
    }

    /**
     * Cập nhật trạng thái thanh toán thành công
     */
    public function markAsPaid(Order $order, ?string $transactionId = null): Order
    {
        return DB::transaction(function () use ($order, $transactionId) {
            $o = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            $updateData = [
                'payment_status' => 'paid',
            ];

            if ($transactionId) {
                $updateData['transaction_id'] = $transactionId;
            }

            if ($o->status === 'pending') {
                $updateData['status'] = 'confirmed';
            }

            $o->update($updateData);
            return $o;
        }, 3);
    }

    public function transition(User $actor, Order $order, string $target): Order
    {
        return DB::transaction(function () use ($actor, $order, $target) {
            $o = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($actor->role !== 'admin') {
                abort_unless($o->user_id === $actor->id && $target === 'cancelled' && in_array($o->status, ['pending', 'cancelled']), 403);
            }

            if ($o->status === $target) {
                return $o;
            }

            if (!in_array($target, self::TRANSITIONS[$o->status] ?? [], true)) {
                $this->fail('Không được chuyển trạng thái này.');
            }

            if ($target === 'cancelled') {
                foreach ($o->items()->orderBy('variant_id')->get() as $line) {
                    $v = ProductVariant::whereKey($line->variant_id)->lockForUpdate()->firstOrFail();
                    $v->increment('stock', $line->quantity);
                }
            }

            $o->update(['status' => $target]);
            return $o;
        }, 3);
    }
}