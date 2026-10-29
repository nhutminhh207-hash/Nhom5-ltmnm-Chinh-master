<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;use Illuminate\Validation\Rule;use App\Models\Category;
class CategoryController extends Controller {
 public function index(){return view('categories',['categories'=>Category::withCount('products')->paginate(15)]);}
 public function create(){return view('category-form',['category'=>new Category]);}
 public function edit(Category $category){return view('category-form',compact('category'));}
 private function data(Request $r,?Category $category=null){$r->merge(['slug'=>$r->input('slug')?:\Illuminate\Support\Str::slug($r->name)]);return $r->validate(['name'=>'required|string|max:255','slug'=>['required','alpha_dash','max:255',Rule::unique('categories')->ignore($category?->id)],'description'=>'nullable|string|max:5000']);}
 public function store(Request $r){Category::create($this->data($r));return redirect('/admin/categories')->with('success','Đã thêm danh mục.');}
 public function update(Request $r,Category $category){$category->update($this->data($r,$category));return redirect('/admin/categories')->with('success','Đã cập nhật danh mục.');}
 public function destroy(Category $category){if($category->products()->exists())return back()->withErrors(['category'=>'Không thể xóa danh mục còn sản phẩm.']);$category->delete();return back()->with('success','Đã xóa danh mục.');}
}
