<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show admin login view
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Authenticate admin session
     */
    public function login(Request $request): RedirectResponse
    {
        $throttleKey = 'admin-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan login admin. Silakan tunggu {$seconds} detik.");
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);

            $admin = Auth::guard('admin')->user();

            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->with('error', 'Akun administrator Anda telah dinonaktifkan.');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Selamat datang di HUMORA Portal, {$admin->name}!");
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withInput($request->only('email'))
            ->with('error', 'Email atau password administrator salah.');
    }

    /**
     * Logout admin session
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('info', 'Anda telah berhasil logout dari Portal Admin HUMORA.');
    }
}
