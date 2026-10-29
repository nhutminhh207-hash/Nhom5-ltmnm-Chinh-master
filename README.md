# EShopper — Nhóm 5: chỉnh sửa dữ liệu và kiểm thử

Dự án Laravel từ ZIP EShopper-Laravel-Nhom5(4) do nhóm cung cấp. Các chức năng có sẵn được giữ nguyên; đợt này phân công 5 người thay dữ liệu demo và kiểm tra theo CN.

| Thành viên | Phụ trách |
|---|---|
| Châu | CN01 — dữ liệu danh mục |
| Danh | CN02 — dữ liệu sản phẩm/biến thể |
| Nhựt | CN03 + CN04 — bộ ca thử tìm/lọc và giỏ |
| Anh Tú | CN05 — dữ liệu checkout/đơn COD |
| Kiều | CN06 + Auth — trạng thái đơn/tài khoản/phân quyền |

- [Phân công từng người](docs/PHAN_CONG_SUA_DU_LIEU.md)
- [Cài đặt và tài liệu dự án gốc](docs/HUONG_DAN_DU_AN_GOC.md)
- [Tạo repository mới và chia nhánh](docs/TAO_GITHUB_MOI.md)
- [Hướng dẫn kiểm thử có sẵn](HUONG_DAN_KIEM_THU.md)
- [Mẫu minh chứng từng người](docs/minh-chung)

## Chạy local

PHP >=8.2, Composer 2, MySQL. Trong thư mục dự án chạy:

```bat
composer install
copy .env.example .env
php artisan key:generate
```

Tạo database fashion_shop_laravel, sửa DB_USERNAME/DB_PASSWORD trong .env theo máy rồi chạy trên database mới:

```bat
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Mở http://127.0.0.1:8000. Tài khoản demo admin@example.test, usera@example.test, userb@example.test; mật khẩu demo DemoShop2026!. Không dùng các tài khoản này cho hệ thống công khai.

Git đồng bộ file; dữ liệu MySQL trên từng máy không tự đồng bộ. Đọc mục dữ liệu chung trong phân công trước khi sửa seeder/import SQL. Mã nguồn chưa được chạy lại trong môi trường chuẩn bị gói này vì không có PHP; gói giữ nguyên code ứng dụng của ZIP và bổ sung tài liệu. Chưa tạo repository từ xa.
