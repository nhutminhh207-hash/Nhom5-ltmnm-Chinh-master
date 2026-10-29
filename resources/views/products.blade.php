@extends('layout')
@section('content')
<h2>Quản lý sản phẩm</h2><a href="{{ url('/admin/products/create') }}" class="btn btn-dark mb-3">Thêm sản phẩm</a>
<form class="mb-3"><input name="q" value="{{ request('q') }}" placeholder="Tìm tên sản phẩm"><button>Tìm</button></form>
<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>Ảnh</th>
            <th>Tên</th>
            <th>Danh mục</th>
            <th>Biến thể / tồn kho</th>
            <th>Hiển thị</th>
            <th>Thao tác</th>
        </tr>@foreach($products as $p)<tr>
            <td><img src="{{ $p->image_url }}" alt="{{ $p->name }}" width="55"></td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name }}</td>
            <td>{{ $p->variants->count() }} / {{ $p->variants->sum('stock') }}</td>
            <td>{{ $p->is_active?'Đang hiện':'Đã ẩn' }}</td>
            <td><a class="btn btn-sm btn-primary" href="{{ url('/admin/products/'.$p->id.'/edit') }}">Sửa</a>
                <form method="post" action="{{ url('/admin/products/'.$p->id) }}" class="d-inline" onsubmit="return confirm('Xóa sản phẩm?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Xóa</button></form>
            </td>
        </tr>@endforeach
    </table>
</div>{{ $products->links() }}
@endsection