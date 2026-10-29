@extends('layout')
@section('content')
<h2>{{ $product->exists?'Sửa':'Thêm' }} sản phẩm</h2>
<form method="post" enctype="multipart/form-data" action="{{ url('/admin/products'.($product->exists?'/'.$product->id:'')) }}">@csrf @if($product->exists)@method('PATCH')@endif<div class="row">
        <div class="col-md-6"><label>Tên sản phẩm</label><input required name="name" class="form-control mb-3" value="{{ old('name',$product->name) }}"><label>Slug (tự động nếu để trống)</label><input name="slug" class="form-control mb-3" value="{{ old('slug',$product->slug) }}"><label>Danh mục</label><select name="category_id" class="form-control mb-3">@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id',$product->category_id)==$c->id)>{{ $c->name }}</option>@endforeach</select><label>Mô tả</label><textarea name="description" class="form-control mb-3">{{ old('description',$product->description) }}</textarea></div>
        <div class="col-md-6"><label>Ảnh mẫu (1–8)</label><input type="number" min="1" max="8" name="image_index" class="form-control mb-3" value="{{ old('image_index',$product->image_index) }}"><label>Tải ảnh riêng (JPG / PNG / WebP, tối đa 4 MB)</label><input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control mb-3"><label>Hiển thị sản phẩm</label><select name="is_active" class="form-control">
                <option value="1" @selected(old('is_active',$product->is_active)==1)>Hiện</option>
                <option value="0" @selected(old('is_active',$product->is_active)==0)>Ẩn</option>
            </select></div>
    </div>
    <h4>Biến thể</h4>
    <p>Mỗi SKU là duy nhất. Ẩn biến thể để ngừng bán và giữ lịch sử đơn hàng.</p>
    <div id="variants">@php($rows=old('variants',$product->exists?$product->variants->toArray():[['sku'=>'','size'=>'M','color'=>'Đen','price'=>150000,'stock'=>20,'is_active'=>1]]))@foreach($rows as $idx=>$v)<div class="variant-row"><input type="hidden" name="variants[{{ $idx }}][id]" value="{{ $v['id']??'' }}">
            <div class="row">@foreach(['sku'=>'SKU','size'=>'Size','color'=>'Màu','price'=>'Giá (đ)','stock'=>'Tồn kho'] as $key=>$label)<div class="col-md-2"><label>{{ $label }}</label><input class="form-control" required name="variants[{{ $idx }}][{{ $key }}]" value="{{ $v[$key]??'' }}" @if(in_array($key,['price','stock'])) type="number" min="0" @endif></div>@endforeach<div class="col-md-2"><label>Hiển thị</label><select class="form-control" name="variants[{{ $idx }}][is_active]">
                        <option value="1" @selected(($v['is_active']??1)==1)>Hiện</option>
                        <option value="0" @selected(($v['is_active']??1)==0)>Ẩn</option>
                    </select></div>
            </div>
        </div>@endforeach</div><button class="btn btn-outline-dark mb-3" type="button" id="add-variant">Thêm biến thể</button><br><button class="btn btn-primary">Lưu sản phẩm</button>
</form>
@endsection

@push('scripts')
<script>
    let variantIndex = Number('{{ count($rows) }}');
    document.getElementById('add-variant').addEventListener('click', () => {
        const idx = variantIndex++,
            wrap = document.createElement('div');
        wrap.className = 'variant-row';
        const row = document.createElement('div');
        row.className = 'row';
        for (const [key, label, defaultValue] of [
                ['sku', 'SKU', ''],
                ['size', 'Size', 'M'],
                ['color', 'Màu', 'Đen'],
                ['price', 'Giá (đ)', '150000'],
                ['stock', 'Tồn kho', '20']
            ]) {
            const col = document.createElement('div');
            col.className = 'col-md-2';
            const l = document.createElement('label');
            l.textContent = label;
            const input = document.createElement('input');
            input.className = 'form-control';
            input.name = `variants[${idx}][${key}]`;
            input.value = defaultValue;
            input.required = true;
            if (['price', 'stock'].includes(key)) {
                input.type = 'number';
                input.min = 0;
            }
            col.append(l, input);
            row.append(col);
        }
        const col = document.createElement('div');
        col.className = 'col-md-2';
        const select = document.createElement('select');
        select.name = `variants[${idx}][is_active]`;
        select.className = 'form-control';
        for (const [val, label] of [
                ['1', 'Hiện'],
                ['0', 'Ẩn']
            ]) {
            const option = document.createElement('option');
            option.value = val;
            option.textContent = label;
            select.append(option);
        }
        col.append(select);
        row.append(col);
        wrap.append(row);
        document.getElementById('variants').append(wrap);
    });
</script>
@endpush