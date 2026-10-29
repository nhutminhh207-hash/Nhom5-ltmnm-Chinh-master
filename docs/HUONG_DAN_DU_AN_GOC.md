# EShopper Laravel — Nhóm 5

Bản chuyển từ dự án Node.js Express + EJS đã gửi sang ứng dụng Laravel 12 + Blade + Eloquent + MySQL. Có cấu trúc khởi động đầy đủ (artisan, bootstrap, public/index.php), controller, model, migration, seeder và giao diện. CSS, ảnh và giấy phép EShopper được giữ từ bản gốc. Giao diện động được viết lại bằng Blade, nên bố cục có điều chỉnh; không cam kết giống từng pixel bản EJS.

## 1. Chạy bằng Laragon trên Windows

Yêu cầu PHP 8.2 trở lên, Composer 2, MySQL 8 hoặc MariaDB tương thích. Bật extension: pdo_mysql, mbstring, openssl, fileinfo, xml, curl. Máy cần Internet lần đầu để Composer tải thư viện. Không cần Node.js hoặc npm để chạy dự án này.

1. Giải nén thư mục EShopper-Laravel vào C:\laragon\www (hoặc thư mục làm việc bạn chọn).
2. Mở Laragon → Start All, mở Terminal và chuyển vào thư mục dự án:
   ```bat
   cd C:\laragon\www\EShopper-Laravel
   composer install
   copy .env.example .env
   php artisan key:generate
   ```
   Với PowerShell, `Copy-Item .env.example .env` cũng dùng được. macOS/Linux: `cp .env.example .env`.
3. Trong phpMyAdmin hoặc HeidiSQL, tạo database mới **fashion_shop_laravel**, charset utf8mb4. Không dùng chung database cũ để tránh trùng bảng. Kiểm tra .env:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fashion_shop_laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Nếu MySQL của bạn có mật khẩu, điền mật khẩu thực tế. Không gửi .env chứa mật khẩu lên GitHub.
4. Tạo bảng và nạp dữ liệu mẫu:
   ```bat
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```
5. Mở http://127.0.0.1:8000. Dừng server bằng Ctrl+C.

Mỗi thành viên chạy các bước trên trên máy riêng. Máy nhóm trưởng tắt không ảnh hưởng website local của thành viên. Seeder tạo sẵn dữ liệu, không cần tự nhập từng bảng. Dữ liệu chỉnh sửa trên máy này không tự đồng bộ sang máy khác.

## 2. Tài khoản và dữ liệu mẫu

Mật khẩu chung: **DemoShop2026!**

| Vai trò | Email |
|---|---|
| Admin | admin@example.test |
| Khách A | usera@example.test |
| Khách B | userb@example.test |

3 danh mục; 15 sản phẩm; mỗi sản phẩm 2 biến thể M/Đen và L/Trắng. Sản phẩm 1 hết hàng, sản phẩm 2 chỉ còn 1 mỗi biến thể để kiểm tra validation. Có 2 đơn pending, giỏ mẫu riêng cho 2 khách và nhận xét mẫu. Admin không được mua hàng.

Dữ liệu Node gốc nằm trong RAM và sinh lại mỗi lần chạy. File ZIP không có dữ liệu giao dịch thực tế từ phiên chạy trước; bản Laravel tái tạo dữ liệu demo tương ứng. MySQL giữ dữ liệu mới sau khi khởi động lại. Seeder bỏ qua khi đã có user hoặc category; không dùng để bổ sung database nhập dở.

**Không chạy `migrate:fresh` trên database có dữ liệu cần giữ.** Nếu chủ động muốn reset demo, dùng database thử nghiệm riêng rồi chạy `php artisan migrate:fresh --seed`.

## 3. Chức năng

- Đăng ký, đăng nhập, đăng xuất; session regeneration, CSRF, giới hạn lần đăng nhập, middleware role.
- CN01: CRUD danh mục; slug duy nhất; chặn xóa khi còn sản phẩm.
- CN02: CRUD sản phẩm, thêm/sửa/ẩn biến thể, SKU duy nhất, giá/tồn kho, ảnh mẫu hoặc upload ảnh. Chặn xóa sản phẩm đã có đơn để bảo toàn lịch sử; có thể ẩn.
- CN03: Tìm theo tên/mô tả/danh mục, lọc danh mục/giá/size/màu trên cùng biến thể và phân trang.
- CN04: Giỏ riêng mỗi user, thêm/cộng dồn/sửa/xóa đúng biến thể; kiểm tra quyền và tồn kho.
- CN05: COD demo, lưu orders/order_items, snapshot giá và tên, transaction và khóa bản ghi, kiểm tra giá/số lượng đã báo, token duy nhất chống lặp.
- CN06: Đơn riêng từng khách; admin xem/cập nhật trạng thái theo luồng; hoàn kho khi hủy đúng một lần.
- Trang chủ, dashboard, nhận xét, showroom Leaflet/OpenStreetMap, báo cáo đã chỉnh đúng kiến trúc Laravel.

Quy tắc trạng thái: pending → confirmed → shipping → completed; pending/confirmed → cancelled. Khách chỉ hủy pending. Giá đơn cũ giữ nguyên khi admin đổi giá sản phẩm. Tổng trên dashboard là doanh thu của đơn completed.

Bản đồ, jQuery và Bootstrap JS dùng CDN nên các phần tương tác đó cần Internet. CSS/ảnh và form mua hàng nằm trong dự án. Showroom là dữ liệu minh họa từ bản gốc. Không có thanh toán online hoặc dịch vụ vận chuyển thực. API POST nằm trong web middleware, phải gửi CSRF token qua header X-CSRF-TOKEN khi gọi bằng AJAX.

## 4. Cấu trúc và phân công

| Thành viên (theo file gốc) | Chức năng | File cần đọc |
|---|---|---|
| Châu | CN01 | app/Http/Controllers/CategoryController.php, app/Models/Category.php |
| Danh | CN02 | ProductController.php, Product.php, ProductVariant.php, resources/views/product-form.blade.php |
| Nhựt | CN03 + CN04 | FoundationController.php, CartController.php, resources/views/shop.blade.php, cart.blade.php |
| Anh Tú | CN05 | app/Services/OrderService.php, OrderController.php, checkout.blade.php |
| Kiều | CN06 + Auth | OrderController.php, AuthController.php, app/Http/Middleware/EnsureRole.php |

- routes/web.php: URL và middleware.
- database/migrations: 8 bảng users, categories, products, product_variants, cart_items, orders, order_items, reviews.
- database/seeders/DatabaseSeeder.php: dữ liệu chung cho nhóm.
- resources/views: Blade; resources/data/showrooms.json: dữ liệu showroom.
- public: assets EShopper và front controller Laravel.
- storage/app/public/products: ảnh upload (liên kết qua public/storage).

Quan hệ: categories 1–N products; products 1–N product_variants; users 1–N cart_items; product_variants 1–N cart_items; users 1–N orders; orders 1–N order_items; product_variants 1–N order_items; products 1–N reviews. Giỏ liên kết user_id trực tiếp, không có bảng carts riêng.

## 5. URL kiểm tra

/; /shop; /shop/3; /login; /register; /cart; /checkout; /orders; /admin; /admin/categories; /admin/products; /admin/orders; /showrooms; /bao-cao.

## 6. Kiểm tra sau cài đặt

```bat
php artisan route:list
php artisan view:cache
php artisan view:clear
```

Đăng nhập khách A → thêm 2 biến thể → đổi số lượng → đặt COD → xem đơn → hủy pending → kiểm tra tồn kho hoàn lại. Đăng nhập khách B không được xem/sửa đơn hoặc giỏ của khách A. Admin tạo/sửa/xóa danh mục trống, kiểm tra chặn xóa danh mục có sản phẩm, sửa giá/stock/ẩn sản phẩm và chuyển trạng thái đơn theo luồng. File scripts/smoke.php có kiểm thử tích hợp riêng bằng SQLite, chỉ chạy trên database thử nghiệm theo HUONG_DAN_KIEM_THU.md.

## 7. Lỗi thường gặp

- `vendor/autoload.php` không tồn tại: chạy composer install trong thư mục có composer.json.
- `No application encryption key`: tạo .env rồi php artisan key:generate.
- `could not find driver`: bật pdo_mysql cho PHP đang dùng; kiểm tra php --ini.
- `Unknown database`: tạo đúng database và kiểm tra .env.
- `Access denied`: kiểm tra DB_USERNAME/DB_PASSWORD, sau đó php artisan config:clear.
- Ảnh upload không hiện: php artisan storage:link; kiểm tra public/storage.
- 419 Page Expired: tải lại form, không tắt cookie, dùng nhất quán 127.0.0.1:8000 thay vì đổi qua localhost giữa phiên.
- Nếu muốn Apache/Laragon virtual host: document root phải trỏ vào public, không trỏ gốc dự án.

## 8. Khác biệt so với Node

Không còn server.js, EJS, DataStore RAM hoặc express-session. Controller và OrderService PHP thực hiện nghiệp vụ; Eloquent truy cập database; migration tạo khóa ngoại; Laravel quản lý session/auth/CSRF. Giữ các chức năng chính và đường dẫn đã có, đồng thời bổ sung validation chặt hơn. Chức năng nhận xét và bản đồ giữ lại; không bổ sung widget thời tiết vì bản gốc chỉ nêu trong báo cáo, chưa có xử lý ứng dụng tương ứng.

Tài liệu tham khảo: https://laravel.com/docs/12.x/installation ; https://laravel.com/docs/12.x/eloquent ; https://laravel.com/docs/12.x/blade ; https://laravel.com/docs/12.x/database .
