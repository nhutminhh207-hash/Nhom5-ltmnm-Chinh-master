# Tạo repository mới

Tên đề xuất: EShopper-Nhom5-Data. Đây là bản mã nguồn chuẩn bị từ ZIP, không phải fork giữ lịch sử commit của repository cũ. Chưa đối chiếu được README trên GitHub vì truy cập đường dẫn mẫu không thành công; phân công dựa vào README và routes trong ZIP.

## Cách không cần GitHub Desktop

1. Đăng nhập GitHub, nhấn dấu + → New repository.
2. Đặt tên EShopper-Nhom5-Data; chọn Private nếu chỉ chia sẻ nội bộ nhóm hoặc Public nếu cần nộp bài công khai. Thêm README ban đầu để có nút Upload files rồi tạo repository.
3. Giải nén gói này. Vào repository → Add file → Upload files. Kéo **nội dung bên trong** EShopper-Nhom5-Data, không kéo nguyên file ZIP và không lồng thêm thư mục gốc.
4. Xác nhận README.md, artisan, composer.json, app, database, public, resources, routes cùng nằm ở gốc. Commit với nội dung “Chuẩn bị EShopper và phân công sửa dữ liệu nhóm 5”.
5. GitHub bỏ qua thư mục rỗng khi upload; nếu gặp lỗi thiếu storage/framework/sessions, views, cache hoặc storage/logs khi chạy, tạo các thư mục đó ở máy local.
6. Settings → Collaborators → thêm 4 thành viên bằng tài khoản GitHub thực tế. Mỗi người tạo nhánh như bảng phân công; sửa tài liệu/seeder thuộc phần mình và mở Pull Request về main.

Gói đã bỏ vendor, .env, cache phiên và log. composer install sẽ tải lại vendor. Giao diện web có giới hạn tải từng file; nếu upload thư mục quá lớn, dùng Git theo cách dưới.

## Cách dùng Git trong Terminal Laragon

Tạo repository trống (không thêm README). Tại thư mục giải nén, thay YOUR_USERNAME bằng tài khoản thực tế:

```bat
git init
git add .
git commit -m "Prepare EShopper data assignment for five members"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/EShopper-Nhom5-Data.git
git push -u origin main
```

Nếu repository đã có README/commit, clone repository mới trước rồi chép nội dung gói vào thư mục clone và commit/push; không force-push để ghi đè lịch sử.

Ví dụ nhánh Châu:

```bat
git switch -c data/CN01-chau
git add docs/minh-chung/Chau-CN01.md
git commit -m "Document CN01 category data changes"
git push -u origin data/CN01-chau
```

Thêm đúng file dữ liệu thực sự thay đổi vào commit. Các nhánh khác dùng tên trong bảng phân công. Nhóm trưởng tích hợp seeder chung vì DatabaseSeeder.php là file dùng chung của các CN. Không đưa .env, dữ liệu cá nhân thật hoặc mật khẩu thật vào commit.
