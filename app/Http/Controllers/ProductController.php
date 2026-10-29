<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\{Product, Category, ProductVariant};

class ProductController extends Controller
{
    public function index(Request $r)
    {
        return view('products', ['products' => Product::with('category', 'variants')->when($r->q, fn($q) => $q->where('name', 'like', '%' . $r->q . '%'))->paginate(15)->withQueryString()]);
    }
    public function create()
    {
        return view('product-form', ['product' => new Product(['is_active' => true, 'image_index' => 1]), 'categories' => Category::all()]);
    }
    public function edit(Product $product)
    {
        return view('product-form', ['product' => $product->load('variants'), 'categories' => Category::all()]);
    }
    private function save(Request $r, Product $product)
    {
        $r->merge(['slug' => $r->input('slug') ?: \Illuminate\Support\Str::slug($r->name), 'is_active' => $r->boolean('is_active')]);
        $d = $r->validate(['name' => 'required|string|max:255', 'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('products')->ignore($product->id)], 'category_id' => 'required|exists:categories,id', 'description' => 'nullable|string|max:10000', 'image_index' => 'required|integer|between:1,8', 'is_active' => 'boolean', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096', 'variants' => 'required|array|min:1|max:50', 'variants.*.id' => 'nullable|integer', 'variants.*.sku' => 'required|string|max:255|distinct', 'variants.*.size' => 'required|string|max:20', 'variants.*.color' => 'required|string|max:50', 'variants.*.price' => 'required|integer|min:0|max:1000000000', 'variants.*.stock' => 'required|integer|min:0|max:1000000', 'variants.*.is_active' => 'required|boolean']);
        foreach ($d['variants'] as $i => $v) {
            $existing = isset($v['id']) ? ProductVariant::find($v['id']) : null;
            if (isset($v['id']) && (!$existing || $existing->product_id !== $product->id)) throw ValidationException::withMessages(['variants' => 'Biến thể không thuộc sản phẩm.']);
            if (ProductVariant::where('sku', $v['sku'])->when($existing, fn($q) => $q->where('id', '!=', $existing->id))->exists()) throw ValidationException::withMessages(["variants.$i.sku" => 'SKU đã được sử dụng.']);
        }
        $upload = $r->file('image')?->store('products', 'public');
        try {
            DB::transaction(function () use ($d, $product, $upload) {
                $data = collect($d)->except(['image', 'variants'])->all();
                if ($upload) $data['image_path'] = $upload;
                $product->fill($data)->save();
                foreach ($d['variants'] as $v) {
                    $id = $v['id'] ?? null;
                    unset($v['id']);
                    if ($id) $product->variants()->whereKey($id)->firstOrFail()->update($v);
                    else $product->variants()->create($v);
                }
            });
        } catch (\Throwable $e) {
            if ($upload) \Illuminate\Support\Facades\Storage::disk('public')->delete($upload);
            throw $e;
        }
    }
    public function store(Request $r)
    {
        $this->save($r, new Product);
        return redirect('/admin/products')->with('success', 'Đã thêm sản phẩm.');
    }
    public function update(Request $r, Product $product)
    {
        $this->save($r, $product);
        return redirect('/admin/products')->with('success', 'Đã cập nhật sản phẩm.');
    }
    public function destroy(Product $product)
    {
        if ($product->variants()->whereHas('orderItems')->exists()) return back()->withErrors(['product' => 'Sản phẩm đã có đơn hàng. Hãy ẩn sản phẩm để giữ lịch sử.']);
        $product->delete();
        return back()->with('success', 'Đã xóa sản phẩm.');
    }
}
