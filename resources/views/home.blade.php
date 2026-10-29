@extends('layout')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <!-- Khối bao bọc có viền đen và bo góc vuông tối giản -->
        <div class="position-relative" style="overflow: hidden;">
            <!-- Link bọc toàn bộ banner -->
            <a href="{{ url('/shop') }}" class="d-block">
                <img src="{{ asset('img/banner-zara.png') }}"
                     style="width: 100%; height: 390px; object-fit: cover;"
                     alt="Bộ sưu tập thời trang Moriva">
            </a>

            <!-- Nút Khám phá nằm giữa theo chiều ngang bên dưới chữ -->
            <a href="{{ url('/shop') }}" 
               class="btn btn-dark text-white px-4 py-2 font-weight-bold position-absolute"
               style="bottom: 25px; left: 30%; transform: translateX(-50%); border-radius: 0; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-size: 13px; letter-spacing: 1px; white-space: nowrap;">
                KHÁM PHÁ CỬA HÀNG <i class="fas fa-arrow-right ml-2" style="font-size: 11px;"></i>
            </a>
        </div>
    </div>
</div>

<h2 class="text-center mb-4">Sản phẩm nổi bật</h2>

<div class="row">
    @foreach($products as $p)
        @include('product-card')
    @endforeach
</div>
@endsection