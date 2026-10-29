@extends('layout')
@section('content')
<h2>{{ $category->exists?'Sửa':'Thêm' }} danh mục</h2>
<form method="post" action="{{ url('/admin/categories'.($category->exists?'/'.$category->id:'')) }}">@csrf @if($category->exists)@method('PATCH')@endif<label>Tên danh mục</label><input name="name" class="form-control mb-3" required value="{{ old('name',$category->name) }}"><label>Slug (để trống để tạo tự động)</label><input name="slug" class="form-control mb-3" value="{{ old('slug',$category->slug) }}"><label>Mô tả</label><textarea name="description" class="form-control mb-3">{{ old('description',$category->description) }}</textarea><button class="btn btn-primary">Lưu danh mục</button></form>
@endsection