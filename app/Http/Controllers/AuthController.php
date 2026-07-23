<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return view('login');
    }

    /**
     * Proses login user
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            AuditLogService::log("Login berhasil ke dalam sistem ({$user->nama} - Role: {$user->role})", $request, $user->id);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect()->route('user.dashboard')->with('success', 'Selamat datang kembali, Operator!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah. Silakan coba lagi.',
        ])->withInput($request->only('email'));
    }

    /**
     * Proses logout user
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            AuditLogService::log("Logout dari sistem ({$user->nama})", $request, $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}