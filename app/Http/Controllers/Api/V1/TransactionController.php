<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Services\FileStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorage
    ) {}

    /**
     * Create a checkout transaction for a subscription plan
     */
    public function checkout(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        if ($validated['payment_method'] === 'qris') {
            $qrisEnabled = (bool) \App\Models\SystemSetting::get('qris_enabled', true);
            if (!$qrisEnabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Metode pembayaran QRIS sedang dinonaktifkan sementara oleh administrator. Silakan pilih metode transfer bank.',
                    'error_code' => 'QRIS_DISABLED',
                ], 422);
            }
        }

        $plan = SubscriptionPlan::where('id', $validated['plan_id'])
            ->where('is_active', true)
            ->firstOrFail();

        // Add-on plan requires an active weekly package
        if ($plan->isAddon() && !$user->hasActiveWeeklySubscription()) {
            return response()->json([
                'success' => false,
                'message' => 'Paket Add-on hanya dapat dibeli jika Anda memiliki Paket Akses Mingguan yang masih aktif.',
                'error_code' => 'WEEKLY_PACKAGE_REQUIRED',
            ], 422);
        }

        // Generate unique transaction code
        $trxCode = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $transaction = Transaction::create([
            'trx_code' => $trxCode,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $plan->price, // Server authoritative price
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan langganan berhasil dibuat. Silakan transfer dan unggah bukti pembayaran.',
            'data' => [
                'transaction' => $this->formatTransaction($transaction->load('plan')),
            ],
        ], 201);
    }

    /**
     * Upload payment proof (end-to-end payment submission)
     */
    public function uploadProof(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'transaction_id' => ['nullable', 'exists:transactions,id'],
            'trx_code' => ['nullable', 'string', 'exists:transactions,trx_code'],
            'plan_id' => ['required_without_all:transaction_id,trx_code', 'nullable', 'exists:subscription_plans,id'],
            'payment_method' => ['required_without_all:transaction_id,trx_code', 'nullable', 'string', 'max:60'],
            'proof_file' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB
        ]);

        // Find existing transaction or create new one
        $transaction = null;

        if (!empty($validated['transaction_id'])) {
            $transaction = Transaction::where('id', $validated['transaction_id'])
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();
        } elseif (!empty($validated['trx_code'])) {
            $transaction = Transaction::where('trx_code', $validated['trx_code'])
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();
        }

        if (!$transaction) {
            $plan = SubscriptionPlan::where('id', $validated['plan_id'])
                ->where('is_active', true)
                ->firstOrFail();

            if ($plan->isAddon() && !$user->hasActiveWeeklySubscription()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paket Add-on hanya dapat dibeli jika Anda memiliki Paket Akses Mingguan yang masih aktif.',
                    'error_code' => 'WEEKLY_PACKAGE_REQUIRED',
                ], 422);
            }

            $trxCode = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $transaction = Transaction::create([
                'trx_code' => $trxCode,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $plan->price, // Authoritative price from DB
                'status' => 'pending',
            ]);
        }

        // Store proof file safely using FileStorageService (no storage:link, no public folder)
        $proofFilename = $this->fileStorage->storeUploadedFile(
            $request->file('proof_file'),
            'proofs',
            'proof_' . $transaction->trx_code,
            ['jpg', 'jpeg', 'png'],
            5242880 // 5MB
        );

        $transaction->update([
            'proof_file' => $proofFilename,
            'status' => 'pending', // Awaiting admin approval
        ]);

        // Record in-app notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Bukti Pembayaran Diunggah',
            'message' => "Bukti pembayaran untuk transaksi {$transaction->trx_code} ({$transaction->plan->name}) telah diterima dan sedang menunggu proses verifikasi.",
            'type' => 'transaction',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diunggah. Pembayaran Anda sedang menunggu verifikasi.',
            'data' => [
                'transaction' => $this->formatTransaction($transaction->load('plan')),
            ],
        ]);
    }

    /**
     * Get transaction history for current user
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $transactions = Transaction::where('user_id', $user->id)
            ->with('plan')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Transaction $trx) => $this->formatTransaction($trx));

        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil.',
            'data' => $transactions,
        ]);
    }

    /**
     * Get single transaction detail (IDOR-protected)
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->with('plan')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil.',
            'data' => [
                'transaction' => $this->formatTransaction($transaction),
            ],
        ]);
    }

    /**
     * Consistent transaction formatting
     */
    protected function formatTransaction(Transaction $trx): array
    {
        return [
            'id' => $trx->id,
            'trx_code' => $trx->trx_code,
            'plan_id' => $trx->plan_id,
            'plan_name' => $trx->plan ? $trx->plan->name : 'Paket Langganan',
            'plan_type' => $trx->plan ? ($trx->plan->plan_type ?? 'weekly') : 'weekly',
            'video_quota' => $trx->plan ? $trx->plan->video_quota : null,
            'duration_days' => $trx->plan ? $trx->plan->duration_days : 7,
            'payment_method' => $trx->payment_method,
            'payment_method_label' => $trx->payment_method_label,
            'amount' => $trx->amount,
            'formatted_amount' => $trx->formatted_amount,
            'status' => $trx->status, // pending, success, rejected
            'proof_uploaded' => !empty($trx->proof_file),
            'verified_at' => $trx->verified_at ? $trx->verified_at->toIso8601String() : null,
            'reject_reason' => $trx->reject_reason,
            'created_at' => $trx->created_at ? $trx->created_at->toIso8601String() : null,
        ];
    }
}
