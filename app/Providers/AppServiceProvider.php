<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider {
 public function register(): void {}
 public function boot(): void {
  Paginator::useBootstrapFour();
  View::composer('layout', function ($view) {
   $view->with('navCategories', \App\Models\Category::orderBy('id')->get());
   $view->with('cartCount', auth()->check() ? \App\Models\CartItem::where('user_id',auth()->id())->sum('quantity') : 0);
  });
 }
}
