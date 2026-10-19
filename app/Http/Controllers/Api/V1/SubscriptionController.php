<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Get active VIP subscription plans
     */
    public function plans(): JsonResponse
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->map(fn (SubscriptionPlan $p) => [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name,
                'duration_days' => $p->duration_days,
                'original_price' => $p->original_price,
                'price' => $p->price,
                'formatted_price' => $p->formatted_price,
                'formatted_original_price' => $p->formatted_original_price,
                'badge_text' => $p->badge_text,
                'benefits' => $p->benefits ?? [],
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar paket VIP berhasil diambil.',
            'data' => $plans,
        ]);
    }

    /**
     * Get official payment methods & bank account info
     */
    public function paymentInfo(): JsonResponse
    {
        $qrisImage = SystemSetting::get('qris_official_image');
        $qrisUrl = !empty($qrisImage) ? url('/api/v1/files/system/' . $qrisImage) : null;

        return response()->json([
            'success' => true,
            'message' => 'Informasi pembayaran resmi berhasil diambil.',
            'data' => [
                'bank_accounts' => [
                    [
                        'code' => 'bca',
                        'bank_name' => 'Bank Central Asia (BCA)',
                        'account_number' => SystemSetting::get('bca_account_number', '8830-192-888'),
                        'account_name' => SystemSetting::get('bca_account_name', 'PT HUMORA MEDIA KREATIF'),
                        'instructions' => 'Transfer manual ke rekening BCA di atas sesuai nominal paket, lalu unggah foto bukti transfer.',
                    ],
                    [
                        'code' => 'mandiri',
                        'bank_name' => 'Bank Mandiri',
                        'account_number' => SystemSetting::get('mandiri_account_number', '137-00-998822-1'),
                        'account_name' => SystemSetting::get('mandiri_account_name', 'HUMORA'),
                        'instructions' => 'Transfer manual ke rekening Mandiri di atas sesuai nominal paket, lalu unggah foto bukti transfer.',
                    ],
                ],
                'qris' => [
                    'code' => 'qris',
                    'is_configured' => !empty($qrisUrl),
                    'qris_image_url' => $qrisUrl,
                    'account_name' => 'HUMORA MEDIA KREATIF',
                    'instructions' => 'Scan QRIS resmi HUMORA menggunakan aplikasi m-Banking atau e-Wallet apa saja, lalu unggah bukti transaksi.',
                ],
            ],
        ]);
    }

    /**
     * Get current user's subscription status
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();

        $activeSub = $user->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest('end_date')
            ->first();

        $pendingTrx = $user->transactions()
            ->with('plan')
            ->where('status', 'pending')
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Status langganan berhasil diambil.',
            'data' => [
                'is_premium' => $user->hasActiveVip(),
                'effective_tier' => $user->effective_tier,
                'valid_until' => $user->valid_until ? $user->valid_until->toIso8601String() : null,
                'remaining_days' => $user->remaining_days,
                'active_plan' => $activeSub ? [
                    'id' => $activeSub->plan->id,
                    'name' => $activeSub->plan->name,
                    'start_date' => $activeSub->start_date->toIso8601String(),
                    'end_date' => $activeSub->end_date->toIso8601String(),
                ] : null,
                'pending_transaction' => $pendingTrx ? [
                    'id' => $pendingTrx->id,
                    'trx_code' => $pendingTrx->trx_code,
                    'plan_name' => $pendingTrx->plan->name,
                    'amount' => $pendingTrx->amount,
                    'formatted_amount' => $pendingTrx->formatted_amount,
                    'payment_method' => $pendingTrx->payment_method,
                    'created_at' => $pendingTrx->created_at->toIso8601String(),
                ] : null,
            ],
        ]);
    }
}
