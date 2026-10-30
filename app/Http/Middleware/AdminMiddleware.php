<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu chưa đăng nhập hoặc role không phải admin -> đá về trang chủ kèm thông báo
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập vào khu vực Quản trị!');
        }

        return $next($request);
    }
}