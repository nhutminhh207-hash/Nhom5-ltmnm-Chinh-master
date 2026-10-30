<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, true)) {
            $errorMessage = in_array('admin', $roles, true)
                ? 'Bạn không có quyền truy cập vào khu vực Quản trị!'
                : 'Bạn không có quyền truy cập vào khu vực này!';

            return redirect('/')->with('error', $errorMessage);
        }

        return $next($request);
    }
}