<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product, Category, ProductVariant, Order};

class FoundationController extends Controller
{
    public function home()
    {
        return view('home', ['products' => Product::where('is_active', true)->with('variants')->take(8)->get(), 'navCategories' => Category::all()]);
    }
    public function shop(Request $r)
    {
        $r->validate([
            'q' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price_range' => 'nullable|in:100_299,300_499,over_500',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
        ]);

        $query = Product::where('is_active', true)
            ->with(['category', 'variants']);

        if ($r->filled('q')) {
            $query->where(function ($q) use ($r) {
                $keyword = '%' . $r->q . '%';

                $q->where('name', 'like', $keyword)
                    ->orWhere('description', 'like', $keyword)
                    ->orWhereHas('category', function ($category) use ($keyword) {
                        $category->where('name', 'like', $keyword);
                    });
            });
        }

        if ($r->filled('category_id')) {
            $query->where('category_id', $r->category_id);
        }

        $query->whereHas('variants', function ($q) use ($r) {
            $q->where('is_active', true);

            if ($r->filled('size')) {
                $q->where('size', $r->size);
            }

            if ($r->filled('color')) {
                $q->where('color', $r->color);
            }

            switch ($r->input('price_range')) {
                case '100_299':
                    $q->whereBetween('price', [100000, 299000]);
                    break;

                case '300_499':
                    $q->whereBetween('price', [300000, 499000]);
                    break;

                case 'over_500':
                    $q->where('price', '>', 500000);
                    break;
            }
        });

        return view('shop', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::all(),
            'sizes' => ProductVariant::distinct()->pluck('size'),
            'colors' => ProductVariant::distinct()->pluck('color'),
        ]);
    }
    public function detail(Product $product)
    {
        abort_unless($product->is_active, 404);
        return view('detail', ['product' => $product->load('variants', 'reviews', 'category')]);
    }
    public function dashboard()
    {
        return view('dashboard', ['productCount' => Product::count(), 'orderCount' => Order::count(), 'pendingCount' => Order::where('status', 'pending')->count(), 'revenue' => Order::where('status', 'completed')->sum('total_amount')]);
    }
    public function showrooms()
    {
        return view('showrooms', ['showrooms' => json_decode(file_get_contents(resource_path('data/showrooms.json')))]);
    }
    public function review(Request $r, Product $product)
    {
        abort_unless($product->is_active, 404);
        $d = $r->validate(['rating' => 'required|integer|between:1,5', 'comment' => 'required|string|max:2000', 'userName' => 'nullable|string|max:100']);
        $review = $product->reviews()->create(['rating' => $d['rating'], 'comment' => $d['comment'], 'user_name' => $r->user()?->name ?? ($d['userName'] ?? 'Khách hàng')]);
        return $r->expectsJson() ? response()->json(['success' => true, 'review' => $review]) : back()->with('success', 'Đã gửi nhận xét.');
    }
}
