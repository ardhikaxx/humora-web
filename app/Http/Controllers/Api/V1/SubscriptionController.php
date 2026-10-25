<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use App\Services\QrisService;
use App\Support\BankDirectory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Get active VIP subscription plans
     */
    public function plans(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user() ?? $request->user('sanctum');
        $canPurchaseAddon = $user ? $user->canPurchaseAddon() : false;

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->map(fn (SubscriptionPlan $p) => [
                'id' => $p->id,
                'code' => $p->code,
                'plan_type' => $p->plan_type ?? 'weekly',
                'name' => $p->name,
                'duration_days' => $p->duration_days,
                'video_quota' => $p->video_quota,
                'formatted_quota' => $p->formatted_quota,
                'original_price' => $p->original_price,
                'price' => $p->price,
                'formatted_price' => $p->formatted_price,
                'formatted_original_price' => $p->formatted_original_price,
                'badge_text' => $p->badge_text,
                'benefits' => $p->benefits ?? [],
                'is_addon' => $p->isAddon(),
                'requires_weekly' => $p->isAddon(),
                'can_purchase' => $p->isAddon() ? $canPurchaseAddon : true,
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
        $qrisEnabled = (bool) SystemSetting::get('qris_enabled', false);
        $qrisImage = SystemSetting::get('qris_official_image');
        $qrisRawPayload = SystemSetting::get('qris_raw_payload', '');
        $qrisAccountName = SystemSetting::get('qris_account_name', 'Global Mobile Content');
        $qrisUrl = ! empty($qrisImage) ? url('/api/v1/files/system/'.$qrisImage) : null;
        $isConfigured = (! empty($qrisUrl) || ! empty($qrisRawPayload));

        $rawBankAccounts = SystemSetting::get('official_bank_accounts');
        if (empty($rawBankAccounts) || ! is_array($rawBankAccounts)) {
            $rawBankAccounts = [
                [
                    'bank_code' => 'bca',
                    'bank_name' => 'Bank Central Asia (BCA)',
                    'account_number' => SystemSetting::get('bca_account_number', '2103039177'),
                    'account_name' => SystemSetting::get('bca_account_name', 'Global Mobile Content'),
                ],
            ];
        }

        $formattedBanks = [];
        foreach ($rawBankAccounts as $index => $b) {
            $bankName = trim($b['bank_name'] ?? 'Bank Transfer');
            $info = BankDirectory::findByCodeOrName($b['bank_code'] ?? $bankName);
            $code = $b['bank_code'] ?? ($info['code'] ?? Str::slug($bankName, '_'));
            if (empty($code)) {
                $code = 'bank_'.($index + 1);
            }

            $logoFile = $info['logo'] ?? ($code.'.png');
            $logoPath = public_path('images/banks/'.$logoFile);
            $v = file_exists($logoPath) ? filemtime($logoPath) : '1';
            $logoUrl = asset('images/banks/'.$logoFile).'?v='.$v;

            $formattedBanks[] = [
                'code' => $code,
                'bank_name' => $bankName,
                'bank_logo' => $logoFile,
                'bank_logo_url' => $logoUrl,
                'account_number' => trim($b['account_number'] ?? ''),
                'account_name' => trim($b['account_name'] ?? ''),
                'instructions' => "Transfer manual ke rekening {$bankName} di atas sesuai nominal paket, lalu unggah foto bukti transfer.",
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Informasi pembayaran resmi berhasil diambil.',
            'data' => [
                'bank_accounts' => $formattedBanks,
                'qris' => [
                    'code' => 'qris',
                    'is_enabled' => $qrisEnabled,
                    'is_configured' => $isConfigured,
                    'has_dynamic_support' => ! empty($qrisRawPayload),
                    'raw_payload' => ! empty($qrisRawPayload) ? $qrisRawPayload : null,
                    'qris_image_url' => $qrisUrl,
                    'account_name' => $qrisAccountName,
                    'instructions' => 'Scan QRIS resmi HUMORA menggunakan aplikasi m-Banking atau e-Wallet apa saja, lalu unggah bukti transaksi.',
                ],
            ],
        ]);
    }

    /**
     * Generate dynamic QRIS string for an exact amount
     */
    public function dynamicQris(Request $request): JsonResponse
    {
        $qrisEnabled = (bool) SystemSetting::get('qris_enabled', true);
        if (! $qrisEnabled) {
            return response()->json([
                'success' => false,
                'message' => 'Metode QRIS saat ini sedang dinonaktifkan oleh administrator.',
            ], 422);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
        ]);

        $rawPayload = SystemSetting::get('qris_raw_payload', '');
        if (empty($rawPayload)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode dasar QRIS belum dikonfigurasi di sistem.',
            ], 404);
        }

        $qrisService = app(QrisService::class);
        $dynamicPayload = $qrisService->makeDynamic($rawPayload, (float) $validated['amount']);
        $accountName = SystemSetting::get('qris_account_name', 'Global Mobile Content');

        return response()->json([
            'success' => true,
            'message' => 'Dynamic QRIS payload berhasil dibuat.',
            'data' => [
                'amount' => (int) round($validated['amount']),
                'account_name' => $accountName,
                'dynamic_payload' => $dynamicPayload,
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

        $accessibleIds = $user->activeAccessibleEpisodeIds();
        $totalCatalog = Episode::where('status', 'published')->count();
        $historicallyGrantedCount = count($user->allHistoricallyGrantedEpisodeIds());

        return response()->json([
            'success' => true,
            'message' => 'Status langganan berhasil diambil.',
            'data' => [
                'is_premium' => $user->hasActiveVip(),
                'effective_tier' => $user->effective_tier,
                'valid_until' => $user->valid_until ? $user->valid_until->toIso8601String() : null,
                'remaining_days' => $user->remaining_days,
                'has_full_access' => $user->hasActiveFullAccess(),
                'has_weekly_active' => $user->hasActiveWeeklySubscription(),
                'can_purchase_addon' => $user->canPurchaseAddon(),
                'accessible_episodes_count' => count($accessibleIds),
                'accessible_episode_ids' => $accessibleIds,
                'total_catalog_episodes' => $totalCatalog,
                'historically_granted_count' => $historicallyGrantedCount,
                'active_plan' => $activeSub ? [
                    'id' => $activeSub->plan->id,
                    'name' => $activeSub->plan->name,
                    'plan_type' => $activeSub->plan_type ?? $activeSub->plan->plan_type,
                    'video_quota' => $activeSub->video_quota ?? $activeSub->plan->video_quota,
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
