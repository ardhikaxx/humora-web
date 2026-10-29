<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Register a new mobile user account
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:6'],
            'gender' => ['nullable', 'in:Laki-laki,Perempuan'],
        ]);

        if (empty($validated['email']) && empty($validated['phone'])) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau nomor telepon wajib diisi untuk pendaftaran.',
                'errors' => [
                    'email' => ['Email atau nomor telepon harus diisi.'],
                ],
            ], 422);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'] ?? 'Laki-laki',
            'tier' => 'free',
            'status' => 'active',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran akun HUMORA berhasil.',
            'data' => [
                'user' => $this->formatUserData($user),
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Login mobile user with email or phone
     */
    public function login(Request $request): JsonResponse
    {
        $throttleKey = 'login-attempt:'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
                'error_code' => 'RATE_LIMIT_EXCEEDED',
            ], 429);
        }

        $validated = $request->validate([
            'login' => ['required_without_all:email,identifier', 'nullable', 'string'],
            'email' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = $validated['login'] ?? $validated['email'] ?? $validated['identifier'] ?? null;

        $user = User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'success' => false,
                'message' => 'Kredensial login tidak cocok dengan data kami.',
                'errors' => [
                    'login' => ['Email/nomor telepon atau password salah.'],
                ],
            ], 401);
        }

        RateLimiter::clear($throttleKey);

        // Check account status
        if ($user->status === 'banned') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda telah dinonaktifkan secara permanen.',
                'error_code' => 'ACCOUNT_BANNED',
                'reason' => $user->status_reason,
            ], 403);
        }

        if ($user->status === 'suspended') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang ditangguhkan sementara.',
                'error_code' => 'ACCOUNT_SUSPENDED',
                'reason' => $user->status_reason,
            ], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        // Merge guest likes from this device/session to the logged-in user
        $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID');
        if ($sessionId) {
            DB::transaction(function () use ($user, $sessionId) {
                Like::whereNull('user_id')
                    ->where('session_id', $sessionId)
                    ->get()
                    ->each(function ($guestLike) use ($user) {
                        // Check if user already liked the same episode or series
                        if ($guestLike->episode_id) {
                            $hasExisting = Like::where('user_id', $user->id)
                                ->where('episode_id', $guestLike->episode_id)
                                ->exists();

                            if ($hasExisting) {
                                $guestLike->delete();
                            } else {
                                try {
                                    $guestLike->update([
                                        'user_id' => $user->id,
                                        'series_id' => null,
                                    ]);
                                } catch (QueryException $e) {
                                    if ($e->getCode() === '23000') {
                                        $guestLike->delete();
                                    } else {
                                        throw $e;
                                    }
                                }
                            }
                        } else {
                            $hasExisting = Like::where('user_id', $user->id)
                                ->where('series_id', $guestLike->series_id)
                                ->whereNull('episode_id')
                                ->exists();

                            if ($hasExisting) {
                                $guestLike->delete();
                            } else {
                                try {
                                    $guestLike->update(['user_id' => $user->id]);
                                } catch (QueryException $e) {
                                    if ($e->getCode() === '23000') {
                                        $guestLike->delete();
                                    } else {
                                        throw $e;
                                    }
                                }
                            }
                        }
                    });
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $this->formatUserData($user),
                'token' => $token,
            ],
        ]);
    }

    /**
     * Get authenticated user profile & current VIP status
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak terautentikasi.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data' => [
                'user' => $this->formatUserData($user),
            ],
        ]);
    }

    /**
     * Update user profile biodata
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,'.$user->id],
            'gender' => ['nullable', 'in:Laki-laki,Perempuan'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $user->phone,
            'gender' => $validated['gender'] ?? $user->gender,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'user' => $this->formatUserData($user->fresh()),
            ],
        ]);
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai.',
                'errors' => [
                    'current_password' => ['Password saat ini salah.'],
                ],
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    /**
     * Logout mobile user by revoking current token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout.',
        ]);
    }

    /**
     * Verify user email for mobile forgot password flow
     */
    public function forgotPasswordVerify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak terdaftar dalam basis data sistem.',
                'errors' => [
                    'email' => ['Alamat email tidak terdaftar dalam sistem kami.'],
                ],
            ], 404);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Akun pengguna ini berstatus non-aktif. Silakan hubungi admin.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email pengguna berhasil diverifikasi.',
            'data' => [
                'email' => $user->email,
                'name' => $user->name,
            ],
        ]);
    }

    /**
     * Reset mobile user password with new credentials
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna dengan email tersebut tidak ditemukan.',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi baru berhasil disimpan! Silakan masuk kembali dengan kata sandi baru Anda.',
        ]);
    }

    /**
     * Format consistent user data dictionary
     */
    protected function formatUserData(User $user): array
    {
        // Load active subscription if any
        $activeSub = $user->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest('end_date')
            ->first();

        $accessibleIds = $user->activeAccessibleEpisodeIds();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'tier' => $user->tier,
            'effective_tier' => $user->effective_tier,
            'is_premium' => $user->hasActiveVip(),
            'valid_until' => $user->valid_until ? $user->valid_until->toIso8601String() : null,
            'remaining_days' => $user->remaining_days,
            'active_plan_name' => $activeSub ? $activeSub->plan->name : null,
            'plan_type' => $activeSub ? ($activeSub->plan_type ?? $activeSub->plan->plan_type) : null,
            'has_full_access' => $user->hasActiveFullAccess(),
            'has_weekly_active' => $user->hasActiveWeeklySubscription(),
            'can_purchase_addon' => $user->canPurchaseAddon(),
            'accessible_episodes_count' => count($accessibleIds),
            'accessible_episode_ids' => $accessibleIds,
            'initials' => $user->initials,
            'status' => $user->status,
            'member_since' => $user->created_at ? $user->created_at->format('d F Y') : null,
        ];
    }
}
