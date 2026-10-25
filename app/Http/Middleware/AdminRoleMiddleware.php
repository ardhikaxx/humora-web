<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request for admin routes with RBAC verification.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi admin berakhir. Silakan login kembali.',
                ], 401);
            }

            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if admin account is active
        if (! $admin->is_active) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun administrator Anda telah dinonaktifkan.',
                ], 403);
            }

            return redirect()->route('admin.login')->with('error', 'Akun administrator Anda telah dinonaktifkan.');
        }

        // If specific roles are required, allow 'admin' and 'super_admin' or matching roles
        if (! empty($roles)) {
            if ($admin->role === 'admin' || $admin->role === 'super_admin') {
                return $next($request);
            }

            if (! in_array($admin->role, $roles, true)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki wewenang untuk tindakan ini.',
                    ], 403);
                }

                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
            }
        }

        return $next($request);
    }
}
