<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 border"><a href="{{ url('/shop/'.$p->id) }}"><img class="product-img" src="{{ $p->image_url }}" alt="{{ $p->name }}"></a>
        <div class="card-body text-center">
            <h6><a href="{{ url('/shop/'.$p->id) }}">{{ $p->name }}</a></h6><strong>{{ number_format($p->variants->where('is_active',1)->min('price') ?? 0) }} đ</strong>
        </div>
        <div class="card-footer text-center bg-light"><a href="{{ url('/shop/'.$p->id) }}">Chọn size / màu</a></div>
    </div>
</div>