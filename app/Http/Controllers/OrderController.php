<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\OrderService;
use App\Models\{Order, CartItem};
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkoutForm(Request $r)
    {
        $items = CartItem::where('user_id', $r->user()->id)->with('variant.product')->get();
        $token = (string)Str::uuid();
        $quote = [];
        foreach ($items as $i) $quote[$i->variant_id] = ['price' => $i->variant->price, 'quantity' => $i->quantity];
        $r->session()->put(['checkout_token' => $token, 'checkout_quote' => $quote]);
        return view('checkout', compact('items', 'token'));
    }
    public function checkout(Request $r, OrderService $service)
    {
        $d = $r->validate([
            'recipient_name' => 'required|string|max:255',
            'phone'          => ['required', 'regex:/^\+?[0-9]{9,15}$/'],
            'address'        => 'required|string|max:1000',
            'checkout_token' => 'required|uuid',
            'payment_method' => 'required|in:cod,bank_transfer',
        ]);

        $existing = Order::where('checkout_token', $d['checkout_token'])->first();
        if ($existing) {
            abort_unless($existing->user_id === $r->user()->id, 403);
            return redirect('/orders/' . $existing->id);
        }

        if ($d['checkout_token'] !== $r->session()->get('checkout_token')) {
            return redirect('/checkout')->withErrors(['token' => 'Phiên đặt hàng hết hạn. Vui lòng xác nhận lại.']);
        }

        $token = $d['checkout_token'];
        $paymentMethod = $d['payment_method'];

        unset($d['checkout_token'], $d['payment_method']);

        // Tạo đơn hàng
        $o = $service->checkout(
            $r->user(),
            $d,
            $token,
            $r->session()->get('checkout_quote', []),
            $paymentMethod
        );

        $r->session()->forget(['checkout_token', 'checkout_quote']);

        return redirect('/orders/' . $o->id);
    }
    public function userOrders()
    {
        return view('orders', ['orders' => Order::where('user_id', Auth::id())->latest()->paginate(15), 'admin' => false]);
    }
    public function adminOrders()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        return view('orders', [
            'orders'       => Order::latest()->paginate(15),
            'admin'        => true,
            'totalRevenue' => $totalRevenue
        ]);
    }
    public function userOrderDetail(Request $r, Order $order)
    {
        abort_unless($order->user_id === $r->user()->id, 403);
        return view('order-detail', ['order' => $order->load('items'), 'admin' => false]);
    }
    public function adminOrderDetail(Order $order)
    {
        return view('order-detail', ['order' => $order->load('items'), 'admin' => true]);
    }
    public function cancelOrder(Request $r, Order $order, OrderService $service)
    {
        $service->transition($r->user(), $order, 'cancelled');
        return back()->with('success', 'Đã hủy đơn và hoàn kho.');
    }
    public function updateStatus(Request $r, Order $order, OrderService $service)
    {
        $d = $r->validate(['status' => 'required|in:pending,confirmed,shipping,completed,cancelled']);
        $service->transition($r->user(), $order, $d['status']);
        return back()->with('success', 'Đã cập nhật trạng thái.');
    }
}
