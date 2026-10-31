<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User, Category, Product, ProductVariant, CartItem, Review};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại để xóa sạch dữ liệu cũ trước khi nạp mới
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        CartItem::truncate();
        Review::truncate();
        ProductVariant::truncate();
        Product::truncate();
        Category::truncate();
        User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        DB::transaction(function () {
            // 1. Users
            $users = [
                ['id' => 1, 'name' => 'Admin demo', 'email' => 'admin@example.test', 'password' => bcrypt('DemoShop2026!'), 'role' => 'admin'],
                ['id' => 2, 'name' => 'User A demo', 'email' => 'usera@example.test', 'password' => bcrypt('DemoShop2026!'), 'role' => 'user'],
                ['id' => 3, 'name' => 'User B demo', 'email' => 'userb@example.test', 'password' => bcrypt('DemoShop2026!'), 'role' => 'user'],
                ['id' => 4, 'name' => 'Nguyễn Minh Nhựt', 'email' => 'nmnhut@example.test', 'password' => bcrypt('DemoShop2026!'), 'role' => 'user'],
            ];
            foreach ($users as $u) {
                User::create($u);
            }

            // 2. Categories
            $categories = [
                ['id' => 1, 'name' => 'Áo thun', 'slug' => 'ao-thun', 'description' => null],
                ['id' => 2, 'name' => 'Áo sơ mi', 'slug' => 'ao-so-mi', 'description' => null],
                ['id' => 3, 'name' => 'Quần', 'slug' => 'quan', 'description' => null],
            ];
            foreach ($categories as $c) {
                Category::create($c);
            }

            // 3. Products
            $products = [
                [1, 1, 'Áo Thun Cổ Tròn', 'ao-thun-co-tron', 'products/KTtfl9P03x91C4HcoGwYPQclcFDPQYjmJMJJg7j5.jpg', 5],
                [2, 2, 'Áo Sơ Mi Tay Ngắn', 'ao-so-mi-tay-ngan', 'products/6DsOdCqB5KcvvjVC7cpAAjwmeKAqrzNIN55eim3g.webp', 2],
                [3, 3, 'Quần Tây Ống Rộng', 'quan-tay-ong-rong', 'products/k3v4LNmijx2nTKN58HlEIcZZF7Qv2V85dd4uogOe.jpg', 3],
                [4, 1, 'Áo Thun Thể Thao', 'ao-thun-the-thao', 'products/mJfJ712ZkrrfLbBLKUjkxjkoHIjc17Ie6JPDlcHO.jpg', 4],
                [5, 2, 'Áo Sơ Mi Tay Dài', 'ao-so-mi-tay-dai', 'products/B1tLpzPEvy3PdvmFB5J42lcCKjgARAlFoExnAlYi.webp', 5],
                [6, 3, 'Quần Tây Basic', 'quan-tay-basic', 'products/OpEjYBT17xfl7mC4tpJ7SRV5FB6QONrKjzwEBxaL.webp', 6],
                [7, 1, 'Áo Thun Trơn', 'ao-thun-tron', 'products/XQdRA7SupnJI4NZkn2df0h5usIwOrJdF09hQtRFt.png', 7],
                [8, 2, 'Áo Sơ Mi Boxy', 'ao-so-mi-boxy', 'products/CVhmyWvjZ3oTtTPezFK2nqmZFBFZ9htB5ySpmicV.jpg', 8],
                [9, 3, 'Quần Short Lửng', 'quan-short-lung', 'products/JTpBUrbeukMsuvtS2oKj1HbWRnvsFJRbkWzUZWZZ.webp', 1],
                [10, 1, 'Áo Thun Có Cổ', 'ao-thun-co-co', 'products/EVCl5AZ1m4W8eL0yMkrM200giphDauHFG1rZ8Edh.jpg', 2],
                [11, 2, 'Áo Sơ Mi Kaki', 'ao-so-mi-kaki', 'products/OZ9QTCfblAE5Ma2gtQsl5K4R6Vcv3P9cDpLbpU18.jpg', 3],
                [12, 3, 'Quần Short', 'quan-short', 'products/RMuvK5v6RsXjx2LYmb9nCLeGRiu3KddgGzxkTVJW.webp', 4],
                [13, 1, 'Áo Thun Jersey', 'ao-thun-jersey', 'products/nqTc7PEvd7XZsZaQ1iUOWgZu4Hm4T2DZnm24VprU.jpg', 5],
                [14, 2, 'Áo Sơ Mi Hawaii', 'ao-so-mi-hawaii', 'products/o4CehHW3v3NpX7zOzfLN2VynVkBwbEpGfIW5xtVo.png', 6],
                [15, 3, 'Quần Baggy', 'quan-baggy', 'products/WPnAnC7wKxvLL4Q9hyAo5vLNwl03ViKOMTP4HPeq.jpg', 7],
            ];

            foreach ($products as [$id, $catId, $name, $slug, $imgPath, $imgIdx]) {
                Product::create([
                    'id' => $id,
                    'category_id' => $catId,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => 'Bộ sưu tập thời trang Zara. Chất liệu thoáng mát, phù hợp đi học và đi làm.',
                    'image_path' => $imgPath,
                    'image_index' => $imgIdx,
                    'is_active' => true,
                ]);
            }

            // 4. Product Variants
            $variants = [
                [1, 1, 'TCT-M', 'M', 'Trắng', 199000, 10],
                [2, 1, 'TCT-L', 'L', 'Trắng', 199000, 10],
                [3, 2, 'SMN-M', 'M', 'Trắng', 329000, 9],
                [4, 2, 'SMN-L', 'L', 'Trắng', 329000, 10],
                [5, 3, 'QOR-M', 'M', 'Đen', 199000, 11],
                [6, 3, 'QOR-L', 'L', 'Đen', 199000, 10],
                [7, 4, 'TTT-M', 'M', 'Xám', 189000, 11],
                [8, 4, 'TTT-L', 'L', 'Xám', 189000, 10],
                [9, 5, 'SMD-M', 'M', 'Trắng', 329000, 10],
                [10, 5, 'SMD-L', 'L', 'Trắng', 329000, 10],
                [11, 6, 'QTB-M', 'M', 'Đen', 189000, 10],
                [12, 6, 'QTB-L', 'L', 'Đen', 189000, 10],
                [13, 7, 'ATT-M', 'M', 'Đen', 189000, 9],
                [14, 7, 'AT-L', 'L', 'Đen', 189000, 10],
                [15, 8, 'SMB-M', 'M', 'Đen', 319000, 10],
                [16, 8, 'SMB-L', 'L', 'Đen', 319000, 10],
                [17, 9, 'QSL-M', 'M', 'Xám', 219000, 10],
                [18, 9, 'QSL-L', 'L', 'Xám', 219000, 10],
                [19, 10, 'TCC-M', 'M', 'Đen', 189000, 10],
                [20, 10, 'TCC-L', 'L', 'Đen', 189000, 10],
                [21, 11, 'SMK-M', 'M', 'Đen', 169000, 10],
                [22, 11, 'SMK-L', 'L', 'Đen', 169000, 10],
                [23, 12, 'QS-M', 'M', 'Xám', 139000, 10],
                [24, 12, 'QS-L', 'L', 'Xám', 139000, 10],
                [25, 13, 'ATJ-M', 'M', 'Xám', 149000, 10],
                [26, 13, 'ATJ-L', 'L', 'Xám', 149000, 10],
                [27, 14, 'SMH-M', 'M', 'Xanh', 149000, 10],
                [28, 14, 'SMH-L', 'L', 'Xanh', 149000, 10],
                [29, 15, 'QB-M', 'M', 'Xám', 219000, 9],
                [30, 15, 'QB-L', 'L', 'Xám', 219000, 10],
            ];

            foreach ($variants as [$id, $prodId, $sku, $size, $color, $price, $stock]) {
                ProductVariant::create([
                    'id' => $id,
                    'product_id' => $prodId,
                    'sku' => $sku,
                    'size' => $size,
                    'color' => $color,
                    'price' => $price,
                    'stock' => $stock,
                    'is_active' => true,
                ]);
            }

            // 5. Cart Items (User B demo - ID 3 có 2 món trong giỏ hàng)
            CartItem::create(['id' => 3, 'user_id' => 3, 'variant_id' => 9, 'quantity' => 1]);
            CartItem::create(['id' => 4, 'user_id' => 3, 'variant_id' => 10, 'quantity' => 1]);

            // 6. Reviews cho 5 sản phẩm đầu tiên
            for ($i = 1; $i <= 5; $i++) {
                Review::create([
                    'id' => $i,
                    'product_id' => $i,
                    'user_name' => 'Khách hàng thân thiết',
                    'rating' => 5,
                    'comment' => 'Chất vải mềm, đóng gói cẩn thận.',
                ]);
            }
        });
    }
}