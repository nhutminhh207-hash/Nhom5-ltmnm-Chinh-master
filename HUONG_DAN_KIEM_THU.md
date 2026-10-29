# Kiểm thử kỹ thuật

Sau khi cài PHP và chạy `composer install`:

```bat
php scripts/smoke.php
```

Cần pdo_sqlite cho script này. Script tự tạo database SQLite tạm trong thư mục hệ thống, override DB_CONNECTION/DB_DATABASE trong process và chạy migration/seed riêng. Không dùng MySQL đang cấu hình của bạn. Chưa chạy được nếu dự án đang có cấu hình cache (`bootstrap/cache/config.php`); hãy `php artisan config:clear` trước khi kiểm thử.

Kiểm tra seed, password demo, đặt hai biến thể, tổng tiền, giảm tồn kho, giỏ riêng, token chống lặp, quyền hủy đơn, hoàn kho một lần, giá đổi, hết kho, transaction rollback, luồng trạng thái, compile và render Blade.

SQLite giúp kiểm tra nghiệp vụ và template nhưng không thay cho kiểm thử cạnh tranh khóa bản ghi trên MySQL. Cần kiểm tra thủ công trên Laragon: đăng nhập, CSRF 419 khi thiếu token, phân quyền admin/user, CRUD category/product, validation SKU, upload ảnh, lọc/phân trang, đơn của hai tài khoản. Kiểm tra đồng thời hai khách mua biến thể stock=1 trên MySQL để xác nhận chỉ một đơn thành công.

## Kết quả kiểm tra bản bàn giao

Đã cài Composer dependencies thành công, khởi động Laravel và xác nhận 37 route. Kiểm tra cú pháp tất cả file PHP đạt. Chạy PHP 8.3 + SQLite: 32 kiểm tra nghiệp vụ/render Blade và 31 kiểm tra HTTP đạt, tổng 63. Các kiểm tra HTTP gồm public pages, login/session, customer/admin pages, quyền truy cập, thêm giỏ/checkout, CRUD sản phẩm và biến thể, chặn xóa danh mục, lọc size/màu/giá và CSRF.

Chưa chạy MySQL/Laragon trên Windows trong môi trường bàn giao này; chưa kiểm thử cạnh tranh nhiều phiên checkout trên MySQL hoặc tải ảnh qua giao diện. Cần kiểm tra các mục này trên máy bạn. ZIP không chứa vendor: chạy composer install để tải đúng phiên bản trong composer.lock.
