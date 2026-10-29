<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Zara — Nhóm 5')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css">

    <style>
        body {
            color: #344054;
        }

        .nav-shop {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .product-img {
            height: 260px;
            object-fit: cover;
            width: 100%;
        }

        .shop-main {
            min-height: 65vh;
        }

        a {
            color: #574336;
        }

        th {
            white-space: nowrap;
        }

        .variant-row {
            background: #f7f5f1;
            padding: 15px;
            margin-bottom: 12px;
        }

        input,
        select,
        textarea {
            max-width: 100%;
        }

        .nav-shop .dropdown-menu {
            min-width: 180px;
        }

        .nav-shop .dropdown-item {
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <div class="bg-secondary px-xl-5 px-3 py-2 d-flex justify-content-between">
        <span>Zara Fashion Shop</span>
    </div>

    <header class="container-fluid px-xl-5 py-4 bg-white border-bottom">
        <div class="row align-items-center">
            <!-- Logo Zara -->
            <div class="col-md-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: 2px;">
                        Zara<span class="text-muted"></span>
                    </h1>
                </a>
            </div>

            <!-- Khung tìm kiếm đen trắng -->
            <div class="col-md-5">
                <form action="{{ url('/shop') }}" method="get" class="input-group">
                    <input class="form-control border-dark"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Tìm sản phẩm thời trang..."
                        style="border-radius: 0;">

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-dark text-white px-4" style="border-radius: 0;">
                            <i class="fas fa-search mr-1"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tài khoản / Đăng nhập -->
            <div class="col-md-3 text-md-right mt-3 mt-md-0 small">
                @auth
                <span class="font-weight-bold text-dark">
                    <i class="fas fa-user-circle mr-1"></i> {{ auth()->user()->name }}
                </span>
                @else
                <span class="text-muted">
                    <i class="far fa-user mr-1"></i> Khách hàng ghé thăm
                </span>
                @endauth
            </div>
        </div>
        <nav class="bg-light border-top border-bottom px-xl-5 px-3 py-3 nav-shop">
            <a href="{{ url('/') }}">Trang chủ</a>
            <a href="{{ url('/shop') }}">Cửa hàng</a>
            <a href="{{ url('/showrooms') }}">Showroom</a>

            @auth
            @if(auth()->user()->role === 'admin')
            <div class="dropdown">
                <button
                    class="btn dropdown-toggle"
                    type="button"
                    id="adminMenu"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    Quản trị
                </button>

                <div class="dropdown-menu" aria-labelledby="adminMenu">
                    <a class="dropdown-item"
                        href="{{ url('/admin/categories') }}">
                        <i class="fas fa-list mr-2" aria-hidden="true"></i>
                        Danh mục
                    </a>

                    <a class="dropdown-item"
                        href="{{ url('/admin/products') }}">
                        <i class="fas fa-tshirt mr-2" aria-hidden="true"></i>
                        Sản phẩm
                    </a>

                    <a class="dropdown-item"
                        href="{{ url('/admin/orders') }}">
                        <i class="fas fa-shopping-bag mr-2" aria-hidden="true"></i>
                        Đơn hàng
                    </a>
                </div>
            </div>
            @else
            <a href="{{ url('/cart') }}">
                Giỏ hàng ({{ $cartCount }})
            </a>

            <a href="{{ url('/orders') }}">Đơn của tôi</a>
            @endif

            <form action="{{ url('/logout') }}"
                method="post"
                class="ml-auto">
                @csrf

                <button type="submit" class="btn btn-sm btn-outline-dark">
                    Đăng xuất
                </button>
            </form>
            @else
            <a href="{{ url('/login') }}" class="ml-auto">Đăng nhập</a>
            <a href="{{ url('/register') }}">Đăng ký</a>
            @endauth
        </nav>
    </header>

    <main class="container-fluid px-xl-5 py-4 shop-main">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container-fluid px-xl-5">
            <div class="row">
                <!-- Cột 1: Brand & Giới thiệu (4 cột) -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h3 class="font-weight-bold text-white mb-3">Zara</h3>
                    <p class="text-white-50 small mb-3">
                        Website bán hàng thời trang hiện đại. Sản phẩm và dữ liệu showroom được sử dụng phục vụ mục đích minh họa đồ án học tập.
                    </p>
                </div>

                <!-- Cột 2: Điều hướng nhanh (2 cột) -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="font-weight-bold text-white mb-3 text-uppercase small" style="letter-spacing: 1px;">Liên kết</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none footer-link">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ url('/shop') }}" class="text-white-50 text-decoration-none footer-link">Cửa hàng</a></li>
                        <li class="mb-2"><a href="{{ url('/showrooms') }}" class="text-white-50 text-decoration-none footer-link">Showroom</a></li>
                    </ul>
                </div>

                <!-- Cột 3: Danh mục sản phẩm (3 cột) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="font-weight-bold text-white mb-3 text-uppercase small" style="letter-spacing: 1px;">Danh mục</h5>
                    <ul class="list-unstyled mb-0">
                        @foreach($navCategories as $c)
                        <li class="mb-2">
                            <a href="{{ url('/shop') . '?category_id=' . $c->id }}" class="text-white-50 text-decoration-none footer-link">
                                {{ $c->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Cột 4: Liên hệ & Hỗ trợ (3 cột - Giúp lấp đầy khoảng trống) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="font-weight-bold text-white mb-3 text-uppercase small" style="letter-spacing: 1px;">Liên hệ</h5>
                    <p class="text-white-50 small mb-2"><i class="fas fa-map-marker-alt mr-2"></i> 140 Lê Trọng Tấn, Phường Tây Thạnh, Quận Tân Phú, TP. Hồ Chí Minh, Việt Nam</p>
                    <p class="text-white-50 small mb-2"><i class="fas fa-envelope mr-2"></i> support@moriva.vn</p>
                    <p class="text-white-50 small mb-0"><i class="fas fa-phone-alt mr-2"></i> 0123 456 789</p>
                </div>
            </div>

            <!-- Đường kẻ phân cách -->
            <hr style="border-color: rgba(255, 255, 255, 0.1);" class="my-4">

            <!-- Dòng bản quyền dưới cùng -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-left text-white-50 small">
                    &copy; {{ date('Y') }} <strong>Zara Fashion</strong>. All rights reserved.
                </div>
                <div class="col-md-6 text-center text-md-right text-white-50 small mt-2 mt-md-0">
                    Giao diện dựa trên EShopper &bull; <a href="{{ asset('template/eshopper/LICENSE.txt') }}" class="text-white-50 text-decoration-underline" target="_blank">License</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>