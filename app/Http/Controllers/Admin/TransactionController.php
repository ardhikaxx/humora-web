<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserVideoAccess;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display transactions list with yellow pending banner, stats, and search
     */
    public function index(Request $request): View
    {
        $query = Transaction::with(['user', 'plan', 'verifier'])
            ->orderByDesc('id');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('trx_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Exact KPI Counts
        $pendingCount = Transaction::where('status', 'pending')->count();
        $successCount = Transaction::where('status', 'success')->count();
        $rejectedCount = Transaction::where('status', 'rejected')->count();
        $totalVerifiedRevenue = (float) Transaction::where('status', 'success')->sum('amount');

        return view('admin.transactions.index', compact(
            'transactions',
            'pendingCount',
            'successCount',
            'rejectedCount',
            'totalVerifiedRevenue'
        ));
    }

    /**
     * Approve manual transaction and atomically grant VIP subscription
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin || ! $admin->canManageFinances()) {
            return back()->with('error', 'Hanya administrator keuangan (Finance/Super Admin) yang berwenang menyetujui pembayaran.');
        }

        try {
            DB::beginTransaction();

            // Lock row for update to prevent concurrent double-approvals
            $transaction = Transaction::lockForUpdate()->findOrFail($id);

            if ($transaction->status !== 'pending') {
                DB::rollBack();

                return back()->with('error', "Transaksi {$transaction->trx_code} sudah berstatus '{$transaction->status}' dan tidak dapat diproses lagi.");
            }

            $user = User::lockForUpdate()->findOrFail($transaction->user_id);
            $plan = $transaction->plan;

            $now = Carbon::now();
            $planType = $plan ? ($plan->plan_type ?? 'weekly') : 'weekly';
            $quota = $plan ? $plan->video_quota : null;

            $startDate = $now;
            $endDate = null;
            $grantedCount = 0;

            if ($planType === 'full') {
                // Skenario Full Access:
                // Akses seluruh katalog video selama 30 hari.
                $endDate = $now->copy()->addDays(30);

                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_type' => 'full',
                    'video_quota' => null,
                    'transaction_id' => $transaction->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                $user->update([
                    'tier' => 'premium',
                    'valid_until' => $endDate,
                ]);

                $notifTitle = 'Akses Penuh Seluruh Katalog Aktif!';
                $notifMsg = "Selamat! Pembayaran transaksi {$transaction->trx_code} ({$plan->name}) berhasil diverifikasi. Anda mendapatkan akses penuh ke seluruh video katalog selama 30 hari hingga {$endDate->isoFormat('D MMMM Y')}.";
                $successMsg = "Transaksi {$transaction->trx_code} berhasil disetujui. Full Access untuk {$user->name} aktif hingga {$endDate->format('d/m/Y')}.";

            } elseif ($planType === 'addon') {
                // Skenario Add-on:
                // Masa aktif mengikuti paket mingguan aktif.
                $activeWeekly = $user->activeWeeklySubscription();
                $endDate = $activeWeekly ? $activeWeekly->end_date : ($user->valid_until && $user->valid_until->greaterThan($now) ? $user->valid_until : $now->copy()->addDays(7));

                // Cari video yang belum pernah dialokasikan kepada pengguna
                $alreadyGrantedIds = UserVideoAccess::where('user_id', $user->id)->pluck('episode_id')->all();

                $allocatedEpisodes = Episode::with('series')
                    ->where('status', 'published')
                    ->whereNotIn('id', $alreadyGrantedIds)
                    ->get()
                    ->filter(fn (Episode $ep) => ! $ep->isFreePreview())
                    ->sortBy([
                        fn ($a, $b) => ($a->series->catalog_order ?? 999) <=> ($b->series->catalog_order ?? 999),
                        fn ($a, $b) => $a->series_id <=> $b->series_id,
                        fn ($a, $b) => $a->episode_number <=> $b->episode_number,
                    ])
                    ->take($quota ?: 5)
                    ->values();

                $grantedCount = $allocatedEpisodes->count();

                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_type' => 'addon',
                    'video_quota' => $grantedCount,
                    'transaction_id' => $transaction->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                foreach ($allocatedEpisodes as $ep) {
                    UserVideoAccess::create([
                        'user_id' => $user->id,
                        'episode_id' => $ep->id,
                        'subscription_id' => $subscription->id,
                        'transaction_id' => $transaction->id,
                        'granted_at' => $now,
                        'expires_at' => $endDate,
                    ]);
                }

                $user->update([
                    'tier' => 'premium',
                    'valid_until' => $endDate,
                ]);

                $notifTitle = 'Paket Add-on Berhasil Aktif!';
                $notifMsg = "Selamat! Pembayaran transaksi {$transaction->trx_code} ({$plan->name}) berhasil diverifikasi. {$grantedCount} video tambahan telah aktif di akun Anda hingga {$endDate->isoFormat('D MMMM Y')}.";
                $successMsg = "Transaksi {$transaction->trx_code} berhasil disetujui. {$grantedCount} video add-on untuk {$user->name} aktif hingga {$endDate->format('d/m/Y')}.";

            } else {
                // Skenario Mingguan (15 Video):
                // Masa aktif 7 hari sejak transaksi disetujui.
                if ($user->hasActiveVip() && $user->valid_until && $user->valid_until->greaterThan($now)) {
                    $endDate = $user->valid_until->copy()->addDays(7);
                } else {
                    $endDate = $now->copy()->addDays(7);
                }

                // Cari 15 video yang belum pernah diberikan sebelumnya
                $alreadyGrantedIds = UserVideoAccess::where('user_id', $user->id)->pluck('episode_id')->all();

                $allocatedEpisodes = Episode::with('series')
                    ->where('status', 'published')
                    ->whereNotIn('id', $alreadyGrantedIds)
                    ->get()
                    ->filter(fn (Episode $ep) => ! $ep->isFreePreview())
                    ->sortBy([
                        fn ($a, $b) => ($a->series->catalog_order ?? 999) <=> ($b->series->catalog_order ?? 999),
                        fn ($a, $b) => $a->series_id <=> $b->series_id,
                        fn ($a, $b) => $a->episode_number <=> $b->episode_number,
                    ])
                    ->take($quota ?: 15)
                    ->values();

                $grantedCount = $allocatedEpisodes->count();

                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_type' => 'weekly',
                    'video_quota' => $grantedCount,
                    'transaction_id' => $transaction->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);

                foreach ($allocatedEpisodes as $ep) {
                    UserVideoAccess::create([
                        'user_id' => $user->id,
                        'episode_id' => $ep->id,
                        'subscription_id' => $subscription->id,
                        'transaction_id' => $transaction->id,
                        'granted_at' => $now,
                        'expires_at' => $endDate,
                    ]);
                }

                $user->update([
                    'tier' => 'premium',
                    'valid_until' => $endDate,
                ]);

                $notifTitle = 'Paket Akses Mingguan Aktif!';
                $notifMsg = "Selamat! Pembayaran transaksi {$transaction->trx_code} ({$plan->name}) berhasil diverifikasi. {$grantedCount} video sketsa komedi telah aktif di akun Anda selama 7 hari hingga {$endDate->isoFormat('D MMMM Y')}.";
                $successMsg = "Transaksi {$transaction->trx_code} berhasil disetujui. {$grantedCount} video mingguan untuk {$user->name} aktif hingga {$endDate->format('d/m/Y')}.";
            }

            // Update transaction to success
            $transaction->update([
                'status' => 'success',
                'verified_by' => $admin->id,
                'verified_at' => $now,
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $user->id,
                'title' => $notifTitle,
                'message' => $notifMsg,
                'type' => 'transaction',
                'is_read' => false,
            ]);

            DB::commit();

            return back()->with('success', $successMsg);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses persetujuan transaksi: '.$e->getMessage());
        }
    }

    /**
     * Reject manual transaction with mandatory reason
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin || ! $admin->canManageFinances()) {
            return back()->with('error', 'Hanya administrator keuangan (Finance/Super Admin) yang berwenang menolak transaksi.');
        }

        $validated = $request->validate([
            'reject_reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::lockForUpdate()->findOrFail($id);

            if ($transaction->status !== 'pending') {
                DB::rollBack();

                return back()->with('error', "Transaksi {$transaction->trx_code} sudah berstatus '{$transaction->status}' dan tidak dapat diproses lagi.");
            }

            $user = User::findOrFail($transaction->user_id);

            $transaction->update([
                'status' => 'rejected',
                'reject_reason' => $validated['reject_reason'],
                'verified_by' => $admin->id,
                'verified_at' => Carbon::now(),
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pembayaran Belum Disetujui',
                'message' => "Pembayaran transaksi {$transaction->trx_code} belum dapat disetujui. Alasan: {$validated['reject_reason']}. Silakan ajukan transaksi ulang dengan bukti yang sah.",
                'type' => 'transaction',
                'is_read' => false,
            ]);

            DB::commit();

            return back()->with('info', "Transaksi {$transaction->trx_code} telah ditolak dengan alasan: {$validated['reject_reason']}.");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses penolakan transaksi: '.$e->getMessage());
        }
    }
}
