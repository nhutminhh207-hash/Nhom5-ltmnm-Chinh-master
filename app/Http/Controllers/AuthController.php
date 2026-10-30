<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth', ['register' => false]);
    }
    public function registerForm()
    {
        return view('auth', ['register' => true]);
    }
    public function login(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (!Auth::attempt($d)) return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
        $r->session()->regenerate();
        return redirect()->intended(Auth::user()->role === 'admin' ? '/admin' : '/shop');
    }
    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users', 'password' => 'required|string|min:8|confirmed']);
        $d['role'] = 'user';
        Auth::login(User::create($d));
        $r->session()->regenerate();
        return redirect('/shop');
    }
    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/');
    }
}
