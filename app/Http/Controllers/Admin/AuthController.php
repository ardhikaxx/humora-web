<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Show admin forgot password form
     */
    public function showForgotPasswordForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.forgot-password');
    }

    /**
     * Verify administrator email in database
     */
    public function verifyForgotPasswordEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email administrator wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Email administrator tidak ditemukan dalam basis data sistem.');
        }

        if (!$admin->is_active) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Akun administrator ini berstatus non-aktif. Silakan hubungi super administrator.');
        }

        // Store verified email in session for password reset
        session(['admin_reset_email' => $admin->email]);

        return redirect()->route('admin.password.reset')
            ->with('success', 'Email administrator berhasil diverifikasi! Silakan tentukan kata sandi baru Anda.');
    }

    /**
     * Show admin reset password form
     */
    public function showResetPasswordForm(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $email = session('admin_reset_email');
        if (!$email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan masukkan email Anda kembali.');
        }

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Update administrator password with new credentials
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $email = session('admin_reset_email');
        if (!$email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan masukkan email Anda kembali.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $admin = Admin::where('email', $email)->first();
        if (!$admin) {
            session()->forget('admin_reset_email');
            return redirect()->route('admin.password.request')
                ->with('error', 'Akun administrator tidak ditemukan.');
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        session()->forget('admin_reset_email');

        return redirect()->route('admin.login')
            ->with('success', 'Kata sandi administrator berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }
}
