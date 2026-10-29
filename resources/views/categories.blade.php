@extends('layout')
@section('content')
<h2>Quản lý danh mục</h2><a class="btn btn-dark mb-3" href="{{ url('/admin/categories/create') }}">Thêm danh mục</a>
<table class="table table-bordered">
    <tr>
        <th>Tên</th>
        <th>Slug</th>
        <th>Số sản phẩm</th>
        <th>Thao tác</th>
    </tr>@foreach($categories as $c)<tr>
        <td>{{ $c->name }}</td>
        <td>{{ $c->slug }}</td>
        <td>{{ $c->products_count }}</td>
        <td><a class="btn btn-sm btn-primary" href="{{ url('/admin/categories/'.$c->id.'/edit') }}">Sửa</a>
            <form method="post" class="d-inline" action="{{ url('/admin/categories/'.$c->id) }}" onsubmit="return confirm('Xóa danh mục này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Xóa</button></form>
        </td>
    </tr>@endforeach
</table>{{ $categories->links() }}
@endsection