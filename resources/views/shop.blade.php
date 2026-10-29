@extends('layout')
@section('content')
<h2 class="mb-4">Cửa hàng thời trang</h2>
<form method="get" class="bg-secondary p-3 mb-4">
    <div class="row">
        <div class="col-md-3"><label>Từ khóa</label><input class="form-control" name="q" value="{{ request('q') }}"></div>
        <div class="col-md-3"><label>Danh mục</label><select class="form-control" name="category_id">
                <option value="">Tất cả</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>@endforeach
            </select></div>@foreach(['size'=>$sizes,'color'=>$colors] as $key=>$values)<div class="col-md-3"><label>{{ $key==='size'?'Size':'Màu sắc' }}</label><select class="form-control" name="{{ $key }}">
                <option value="">Tất cả</option>@foreach($values as $value)<option @selected(request($key)===$value)>{{ $value }}</option>@endforeach
            </select></div>@endforeach<div class="col-md-6 mt-3">
            <label>Phân khúc giá</label>

            <select class="form-control" name="price_range">
                <option value="">Tất cả mức giá</option>

                <option value="100_299"
                    @selected(request('price_range')==='100_299' )>
                    100.000 – 299.000 VNĐ
                </option>

                <option value="300_499"
                    @selected(request('price_range')==='300_499' )>
                    300.000 – 499.000 VNĐ
                </option>

                <option value="over_500"
                    @selected(request('price_range')==='over_500' )>
                    Trên 500.000 VNĐ
                </option>
            </select>
        </div>
        <div class="col-md-6 mt-5">
            <button class="btn btn-dark text-white px-4" style="border-radius: 0;">Lọc sản phẩm</button>
            <a href="{{ url('/shop') }}" class="btn btn-outline-dark px-4" style="border-radius: 0;">Bỏ lọc</a>
        </div>
    </div>
</form>
<p>{{ $products->total() }} sản phẩm phù hợp</p>
<div class="row">@forelse($products as $p)@include('product-card')@empty<p class="p-3">Không tìm thấy sản phẩm.</p>@endforelse</div>{{ $products->links() }}
@endsection