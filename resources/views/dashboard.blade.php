@extends('layout')
@section('content')
<h2>Quản trị cửa hàng</h2>
<div class="row my-4">@foreach(['Sản phẩm'=>$productCount,'Đơn hàng'=>$orderCount,'Chờ xác nhận'=>$pendingCount,'Doanh thu đơn hoàn tất'=>number_format($revenue).' đ'] as $label=>$value)<div class="col-md-3">
        <div class="border p-4">
            <h5>{{ $label }}</h5>
            <h3>{{ $value }}</h3>
        </div>
    </div>@endforeach</div>
<p><a class="btn btn-primary" href="{{ url('/admin/categories') }}">Quản lý danh mục</a> <a class="btn btn-primary" href="{{ url('/admin/products') }}">Quản lý sản phẩm</a> <a class="btn btn-primary" href="{{ url('/admin/orders') }}">Quản lý đơn hàng</a></p>
@endsection