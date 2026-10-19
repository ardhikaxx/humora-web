<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display mobile users list with search and tier/status filters
     */
    public function index(Request $request): View
    {
        $query = User::orderByDesc('id');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($tierFilter = $request->input('tier')) {
            if ($tierFilter === 'free') {
                $query->where('tier', 'free');
            } elseif ($tierFilter === 'premium') {
                $query->where('tier', 'premium')
                    ->where('valid_until', '>=', Carbon::now());
            } elseif ($tierFilter === 'expired') {
                $query->where('tier', 'premium')
                    ->where(function ($q) {
                        $q->whereNull('valid_until')
                            ->orWhere('valid_until', '<', Carbon::now());
                    });
            }
        }

        if ($statusFilter = $request->input('status')) {
            $query->where('status', $statusFilter);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show detailed user profile with subscriptions, transactions, and watch history
     */
    public function show(int $id): View
    {
        $user = User::with([
            'subscriptions.plan',
            'transactions.plan',
            'watchHistories.episode.series',
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Update user account status (active, suspended, banned) with mandatory reason
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:active,suspended,banned'],
            'status_reason' => ['required_if:status,suspended,banned', 'nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'status' => $validated['status'],
            'status_reason' => $validated['status'] !== 'active' ? $validated['status_reason'] : null,
        ]);

        // Revoke all tokens immediately if suspended or banned
        if (in_array($validated['status'], ['suspended', 'banned'], true)) {
            $user->tokens()->delete();
        }

        $statusLabel = match ($validated['status']) {
            'active' => 'diaktifkan kembali',
            'suspended' => 'ditangguhkan sementara',
            'banned' => 'diblokir permanen',
        };

        return back()->with('success', "Status akun '{$user->name}' berhasil {$statusLabel}.");
    }
}
