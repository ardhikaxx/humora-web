<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
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
        if (!$admin || !$admin->canManageFinances()) {
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

            // Calculate subscription duration:
            // If user currently has active VIP, extend from current valid_until so no remaining days are lost!
            if ($user->hasActiveVip() && $user->valid_until && $user->valid_until->greaterThan($now)) {
                $startDate = $now;
                $endDate = $user->valid_until->copy()->addDays($plan->duration_days);
            } else {
                $startDate = $now;
                $endDate = $now->copy()->addDays($plan->duration_days);
            }

            // Create exactly one Subscription record linked to this transaction
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'transaction_id' => $transaction->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            // Update user tier and validity
            $user->update([
                'tier' => 'premium',
                'valid_until' => $endDate,
            ]);

            // Update transaction to success
            $transaction->update([
                'status' => 'success',
                'verified_by' => $admin->id,
                'verified_at' => $now,
            ]);

            // Create notification for user
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pembayaran VIP Disetujui!',
                'message' => "Selamat! Pembayaran transaksi {$transaction->trx_code} ({$plan->name}) berhasil diverifikasi. Akun Premium VIP Anda aktif hingga {$endDate->isoFormat('D MMMM Y')}.",
                'type' => 'transaction',
                'is_read' => false,
            ]);

            DB::commit();

            return back()->with('success', "Transaksi {$transaction->trx_code} berhasil disetujui. Akses VIP untuk {$user->name} telah aktif hingga {$endDate->format('d/m/Y')}.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses persetujuan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Reject manual transaction with mandatory reason
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->canManageFinances()) {
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
                'title' => 'Pembayaran Ditolak',
                'message' => "Pembayaran transaksi {$transaction->trx_code} ditolak oleh tim admin. Alasan: {$validated['reject_reason']}. Silakan ajukan transaksi ulang dengan bukti yang sah.",
                'type' => 'transaction',
                'is_read' => false,
            ]);

            DB::commit();

            return back()->with('info', "Transaksi {$transaction->trx_code} telah ditolak dengan alasan: {$validated['reject_reason']}.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penolakan transaksi: ' . $e->getMessage());
        }
    }
}
