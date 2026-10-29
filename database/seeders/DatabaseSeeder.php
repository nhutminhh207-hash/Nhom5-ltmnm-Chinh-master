<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Category, Product, ProductVariant, CartItem, Order, Review};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::exists() || Category::exists()) {
            $this->command?->warn('Bỏ qua seed: database đã có dữ liệu.');
            return;
        }
        DB::transaction(function () {
            $admin = User::create(['name' => 'Admin demo', 'email' => 'admin@example.test', 'password' => 'DemoShop2026!', 'role' => 'admin']);
            $a = User::create(['name' => 'User A demo', 'email' => 'usera@example.test', 'password' => 'DemoShop2026!', 'role' => 'user']);
            $b = User::create(['name' => 'User B demo', 'email' => 'userb@example.test', 'password' => 'DemoShop2026!', 'role' => 'user']);
            $cats = [];
            foreach (['ao-thun' => 'Áo thun', 'ao-so-mi' => 'Áo sơ mi', 'quan' => 'Quần'] as $slug => $name) $cats[] = Category::create(compact('slug', 'name'));
            for ($i = 1; $i <= 15; $i++) {
                $p = Product::create(['category_id' => $cats[($i - 1) % 3]->id, 'name' => "Sản phẩm demo $i", 'slug' => "san-pham-demo-$i", 'description' => 'Bộ sưu tập thời trang Zara. Chất liệu thoáng mát, phù hợp đi học và đi làm.', 'image_index' => (($i - 1) % 8) + 1, 'is_active' => true]);
                foreach ([['M', 'Đen', 0], ['L', 'Trắng', 10000]] as [$size, $color, $bonus]) $p->variants()->create(['sku' => 'DEMO-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-' . $size, 'size' => $size, 'color' => $color, 'price' => 150000 + $i * 1000 + $bonus, 'stock' => $i === 1 ? 0 : ($i === 2 ? 1 : 20), 'is_active' => true]);
                if ($i <= 5) $p->reviews()->create(['user_name' => 'Khách hàng thân thiết', 'rating' => 5, 'comment' => 'Chất vải mềm, đóng gói cẩn thận.']);
            }
            foreach ([[$a, 'DEMO-03-M'], [$b, 'DEMO-04-M']] as [$u, $sku]) {
                $v = ProductVariant::where('sku', $sku)->first();
                $o = Order::create(['user_id' => $u->id, 'checkout_token' => (string)\Illuminate\Support\Str::uuid(), 'recipient_name' => $u->name, 'phone' => '0900000000', 'address' => 'Địa chỉ demo, không phải thông tin thật', 'status' => 'pending', 'payment_method' => 'cod', 'total_amount' => $v->price * 2]);
                $o->items()->create(['variant_id' => $v->id, 'product_name' => $v->product->name, 'sku' => $v->sku, 'size' => $v->size, 'color' => $v->color, 'unit_price' => $v->price, 'quantity' => 2, 'line_total' => $v->price * 2]);
                $v->decrement('stock', 2);
                foreach (['DEMO-05-M', 'DEMO-05-L'] as $cartSku) CartItem::create(['user_id' => $u->id, 'variant_id' => ProductVariant::where('sku', $cartSku)->value('id'), 'quantity' => 1]);
            }
        });
    }
}
