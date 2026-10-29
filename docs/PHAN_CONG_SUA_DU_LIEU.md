# Phân công chỉnh sửa dữ liệu — Nhóm 5

Dự án đã có chức năng. Công việc đợt này là thay dữ liệu demo, kiểm tra kết quả và ghi nhận đóng góp; không yêu cầu mỗi người viết lại chức năng. Phân công giữ đúng tên trong README và routes của ZIP hoàn thiện.

| Người | CN | Dữ liệu phụ trách | Nhánh |
|---|---|---|---|
| Châu | CN01 | Danh mục: tên, slug; nội dung danh mục hiển thị | data/CN01-chau |
| Danh | CN02 | Sản phẩm, mô tả, ảnh, danh mục liên kết, SKU, size, màu, giá, tồn kho | data/CN02-danh |
| Nhựt | CN03 + CN04 | Bộ truy vấn tìm/lọc và dữ liệu giỏ hàng riêng của khách | data/CN03-CN04-nhut |
| Anh Tú | CN05 | Thông tin người nhận và đơn COD phát sinh từ checkout | data/CN05-anhtu |
| Kiều | CN06 + Auth | Trạng thái đơn, tài khoản demo và vai trò phù hợp | data/CN06-auth-kieu |

## 1. Châu — CN01: Danh mục

Đăng nhập admin tại /login, mở /admin/categories. Đổi tên danh mục theo bộ sưu tập nhóm thống nhất, tạo thêm một danh mục trống để thử xóa. Kiểm tra slug không trùng và danh mục chứa sản phẩm không xóa được. Giữ ID của danh mục đang có sản phẩm; Danh dùng các ID này để liên kết sản phẩm. Không tự thêm trường status vì bảng hiện tại không có trường đó.

Đọc app/Http/Controllers/CategoryController.php, app/Models/Category.php và resources/views/categories.blade.php, category-form.blade.php. Ghi lại tối thiểu 3 danh mục trước/sau và ảnh tạo, sửa, xóa danh mục trống, chặn xóa danh mục có sản phẩm. Kiến thức: CRUD, validation, Eloquent và quan hệ 1–N. Đối chiếu số Lab với tài liệu môn học của nhóm; gói này không có các Lab để xác nhận số.

## 2. Danh — CN02: Sản phẩm và biến thể

Mở /admin/products. Thay tên “Sản phẩm demo” bằng tên thời trang cụ thể, sửa mô tả và ảnh phù hợp; phân loại theo danh mục Châu đã thống nhất. Sửa giá/tồn kho tại biến thể, không tại bảng products: ứng dụng hiện lưu price và stock trong product_variants. Giữ SKU duy nhất; không đổi SKU đang được DatabaseSeeder.php dùng để tạo đơn/giỏ demo nếu chưa sửa đồng bộ các tham chiếu.

Chuẩn bị ít nhất 15 sản phẩm để thử phân trang 12 sản phẩm/trang; có size M/L, màu Đen/Trắng, giá ở cả ba khoảng 100000–299000, 300000–499000, lớn hơn 500000. Giữ một biến thể hết hàng, một biến thể tồn 1. Các khoảng hiện tại bỏ trống 299001–299999 và 499001–500000; ghi nhận đây là điểm cần kiểm tra riêng của CN03 nếu nhóm muốn sửa logic lọc, không nhầm là dữ liệu mất.

Đọc ProductController.php, Product.php, ProductVariant.php và product-form.blade.php. Kiểm tra sửa giá, thêm/ẩn biến thể, ảnh hiển thị, SKU trùng bị từ chối, sản phẩm có lịch sử đơn được bảo vệ. Ghi bảng tên/size/màu/giá/tồn kho và ảnh minh chứng. Kiến thức: CRUD có quan hệ, upload, validation, Eloquent.

## 3. Nhựt — CN03 và CN04: Tìm/lọc và giỏ

CN03 sử dụng dữ liệu CN01–CN02, không có bảng dữ liệu riêng. Thống nhất với Danh dữ liệu để kiểm tra: tên có từ “áo”, một danh mục cụ thể, size M, màu Đen, từng khoảng giá và truy vấn không có kết quả. Lập ít nhất 8 ca thử, gồm kết hợp size/màu/giá cùng một biến thể và chuyển trang vẫn giữ bộ lọc. Không sửa danh mục/sản phẩm đã giao cho Châu/Danh nếu chưa phối hợp.

CN04 đăng nhập khách A tại /cart; thêm M/Đen và L/Trắng của cùng sản phẩm, thử cộng dồn một biến thể, đổi số lượng và xóa một dòng. Đăng nhập khách B để kiểm tra giỏ tách riêng; thử số lượng vượt tồn kho. Dữ liệu CN04 là cart_items(user_id, variant_id, quantity), không có bảng carts. Ghi giá từng dòng và tổng để đối chiếu.

Đọc FoundationController.php (shop), CartController.php, shop.blade.php và cart.blade.php. Nộp bảng ca thử tìm/lọc, bảng giỏ trước/sau và ảnh. Kiến thức: Request, query builder/Eloquent, pagination, session/auth và validation.

## 4. Anh Tú — CN05: Đặt hàng COD

Dùng giỏ do Nhựt chuẩn bị, vào /checkout. Nhập tên, số điện thoại và địa chỉ giả lập có nhãn DEMO; không dùng thông tin cá nhân thật. Tạo tối thiểu 3 đơn bằng giao diện checkout. Ghi sản phẩm/biến thể/số lượng, giá lúc đặt, tổng tiền và mã đơn. Kiểm tra thiếu thông tin bị từ chối, đặt thành công lưu orders và order_items, giỏ được xử lý và tồn kho giảm đúng.

Không sửa thủ công total_amount hoặc line_total trong phpMyAdmin để làm đẹp số liệu. Đơn giữ snapshot product_name, sku, size, color, unit_price: khi Danh đổi giá mới, giá đơn cũ vẫn phải giữ. Không đổi trạng thái thay Kiều; bàn giao mã đơn pending để Kiều kiểm tra. Đọc OrderController.php phần checkout, app/Services/OrderService.php, checkout.blade.php. Kiến thức: transaction, quan hệ đơn/chi tiết, validation và chống gửi lặp.

## 5. Kiều — CN06 và Auth: Đơn, tài khoản, phân quyền

Nhận mã đơn từ Anh Tú. Đăng nhập admin, vào /admin/orders; chuyển một đơn pending → confirmed → shipping → completed. Tạo đơn khác để kiểm tra pending/confirmed → cancelled; đối chiếu tồn kho hoàn đúng một lần. Khách chỉ hủy pending. Giá trị lưu trong database là completed/cancelled, không phải done/cancel.

Tài khoản chỉ có role admin/user theo routes hiện tại; không tạo staff và cho rằng hệ thống hỗ trợ role đó. Có thể đăng ký tài khoản khách demo qua /register. Nếu cần sửa tên/email/tài khoản seed, dùng Eloquent/Seeder; không ghi mật khẩu dạng văn bản thẳng vào bảng users. Kiểm tra khách không vào trang admin, khách B không xem đơn khách A và đăng xuất thành công. ZIP hiện không có route xóa đơn; không nhận nhiệm vụ “xóa đơn hoàn tất” như một chức năng đã có.

Đọc OrderController.php phần quản lý đơn, AuthController.php, EnsureRole.php và orders.blade.php, order-detail.blade.php, auth.blade.php. Nộp bảng mã đơn/trạng thái trước-sau, kiểm tra tồn kho và ảnh phân quyền. Kiến thức: middleware, Auth, state transition và quan hệ dữ liệu.

## 6. Thứ tự phối hợp và dữ liệu chung

Châu thống nhất danh mục → Danh chuẩn hóa sản phẩm/biến thể → Nhựt thử tìm/lọc và chuẩn bị giỏ → Anh Tú tạo đơn → Kiều cập nhật trạng thái. Mọi người có thể đọc code và chuẩn bị ca thử cùng lúc, nhưng dữ liệu liên quan cần bàn giao theo thứ tự trên.

Mỗi máy có MySQL riêng. Git chỉ đồng bộ file, không đồng bộ thay đổi trong phpMyAdmin. Muốn chia sẻ dữ liệu: nhóm trưởng tích hợp thay đổi seed vào database/seeders/DatabaseSeeder.php cho database mới, hoặc nhận dump SQL demo sau khi thống nhất và lưu database/dumps/fashion_shop_laravel.sql. Khi import dump đã có bảng/dữ liệu, không chạy migrate --seed lại như một bước bắt buộc. Sao lưu dữ liệu cần giữ trước khi import; dùng database thử riêng để tránh ghi đè.

Seeder hiện bỏ qua toàn bộ nếu đã có user hoặc category. Sửa seeder rồi chạy db:seed trên database đang dùng sẽ không tự cập nhật. Không chạy migrate:fresh trên dữ liệu cần giữ. Chỉ dùng migrate:fresh --seed khi chủ động muốn xóa và tạo lại database demo riêng.

Mỗi người ghi thay đổi ở docs/minh-chung/<ten>-CNxx.md; dump riêng nếu có thì đặt docs/minh-chung/<ten>-demo.sql, chỉ chứa dữ liệu giả lập. Nhóm trưởng review và tích hợp thành một bản dữ liệu chung; không nhập chồng 5 dump toàn database vì ID, giỏ và đơn có liên hệ chặt.

## 7. Cách trình bày phần làm

“Tôi phụ trách CN…; dữ liệu ban đầu…; tôi thay đổi… bằng giao diện… hoặc seeder…; kiểm tra… cho kết quả…; dữ liệu liên kết với CN… thông qua…; minh chứng nằm ở…”. Phân biệt chức năng có sẵn với phần dữ liệu/kiểm tra thực sự đã thực hiện. Chỉ ghi hoàn thành khi có kết quả, không đánh dấu trước.

Tiêu chí bàn giao: mở website được, dữ liệu phù hợp chức năng, quan hệ không đứt, có bảng trước/sau, có ca thử và ảnh minh chứng, không đưa .env/mật khẩu thật lên GitHub.
