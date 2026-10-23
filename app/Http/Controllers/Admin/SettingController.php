<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use App\Services\FileStorageService;
use App\Support\BankDirectory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorage
    ) {}

    /**
     * Display system settings form
     */
    public function index(): View
    {
        $freeEpisodes = SystemSetting::get('free_preview_episodes', 2);
        $bankAccounts = SystemSetting::get('official_bank_accounts');
        if (empty($bankAccounts) || !is_array($bankAccounts)) {
            $bankAccounts = [
                [
                    'bank_code' => 'bca',
                    'bank_name' => 'Bank Central Asia (BCA)',
                    'account_number' => SystemSetting::get('bca_account_number', '8830-192-888'),
                    'account_name' => SystemSetting::get('bca_account_name', 'PT HUMORA MEDIA KREATIF'),
                ],
                [
                    'bank_code' => 'mandiri',
                    'bank_name' => 'Bank Mandiri',
                    'account_number' => SystemSetting::get('mandiri_account_number', '137-00-998822-1'),
                    'account_name' => SystemSetting::get('mandiri_account_name', 'HUMORA'),
                ],
            ];
        }

        // Augment each bank with metadata from BankDirectory
        foreach ($bankAccounts as &$bank) {
            $info = BankDirectory::findByCodeOrName($bank['bank_code'] ?? $bank['bank_name'] ?? '');
            if ($info) {
                $bank['bank_code'] = $info['code'];
                $bank['bank_logo'] = $info['logo'];
                $bank['pattern'] = implode(',', $info['pattern']);
                $bank['placeholder'] = $info['placeholder'];
                $bank['category'] = $info['category'];
            } else {
                $bank['bank_code'] = $bank['bank_code'] ?? Str::slug($bank['bank_name'] ?? 'bank', '_');
                $bank['bank_logo'] = null;
                $bank['pattern'] = '4,4,4,4';
                $bank['placeholder'] = 'Nomor rekening bank';
                $bank['category'] = 'Bank Lainnya';
            }
        }
        unset($bank);

        $allBanks = BankDirectory::all();
        $groupedBanks = BankDirectory::grouped();
        $qrisImage = SystemSetting::get('qris_official_image', '');

        // Ensure default 4 plans exist with exact client parameters
        $defaultPlansData = [
            'weekly_15' => [
                'plan_type' => 'weekly',
                'name' => 'Paket Akses Mingguan (15 Video)',
                'duration_days' => 7,
                'video_quota' => 15,
                'original_price' => 25000,
                'price' => 15000,
                'badge_text' => 'Akses Mingguan',
                'benefits' => [
                    'Akses 15 video sketsa komedi selama 7 hari',
                    'Bebas tonton ulang video dalam paket sepuasnya',
                    'Streaming HD tanpa jeda buffering',
                    'Dukungan pembelian Add-on video tambahan',
                ],
                'is_active' => true,
            ],
            'addon_5' => [
                'plan_type' => 'addon',
                'name' => 'Add-on 5 Video',
                'duration_days' => 0,
                'video_quota' => 5,
                'original_price' => 10000,
                'price' => 7500,
                'badge_text' => 'Ekstra 5 Video',
                'benefits' => [
                    'Tambahan 5 video katalog baru',
                    'Masa aktif mengikuti paket mingguan aktif',
                    'Bebas tonton ulang selama masa aktif',
                    'Video baru tidak pernah mengulang',
                ],
                'is_active' => true,
            ],
            'addon_10' => [
                'plan_type' => 'addon',
                'name' => 'Add-on 10 Video',
                'duration_days' => 0,
                'video_quota' => 10,
                'original_price' => 15000,
                'price' => 12500,
                'badge_text' => 'Paling Populer',
                'benefits' => [
                    'Tambahan 10 video katalog baru',
                    'Masa aktif mengikuti paket mingguan aktif',
                    'Bebas tonton ulang selama masa aktif',
                    'Video baru tidak pernah mengulang',
                ],
                'is_active' => true,
            ],
            'full_30' => [
                'plan_type' => 'full',
                'name' => 'Full Access Seluruh Katalog',
                'duration_days' => 30,
                'video_quota' => null,
                'original_price' => 99000,
                'price' => 49000,
                'badge_text' => 'Akses Penuh 30 Hari',
                'benefits' => [
                    'Akses tanpa batas seluruh video katalog (~68+ video)',
                    'Bebas nonton ulang sepuasnya selama 30 hari',
                    'Tidak perlu membeli paket mingguan atau add-on',
                    'Streaming HD tercepat tanpa hambatan',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($defaultPlansData as $code => $data) {
            SubscriptionPlan::firstOrCreate(['code' => $code], $data);
        }

        $plans = SubscriptionPlan::orderBy('id')->get()->keyBy('code');
        $qrisEnabled = (bool) SystemSetting::get('qris_enabled', true);
        $qrisRawPayload = SystemSetting::get('qris_raw_payload', '');
        $qrisAccountName = SystemSetting::get('qris_account_name', 'PT HUMORA MEDIA KREATIF');

        return view('admin.settings.index', compact(
            'freeEpisodes',
            'bankAccounts',
            'allBanks',
            'groupedBanks',
            'qrisImage',
            'qrisEnabled',
            'qrisRawPayload',
            'qrisAccountName',
            'plans'
        ));
    }

    /**
     * Update system settings & official payment configurations
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !$admin->isSuperAdmin()) {
            return back()->with('error', 'Hanya Administrator yang berwenang mengubah pengaturan sistem.');
        }

        $validated = $request->validate([
            'free_preview_episodes' => ['required', 'integer', 'min:1', 'max:5'],
            'bank_accounts' => ['required', 'array', 'min:1'],
            'bank_accounts.*.bank_name' => ['required', 'string', 'max:100'],
            'bank_accounts.*.bank_code' => ['nullable', 'string', 'max:50'],
            'bank_accounts.*.account_number' => ['required', 'string', 'max:50'],
            'bank_accounts.*.account_name' => ['required', 'string', 'max:100'],
            'plans' => ['nullable', 'array'],
            'plans.*.price' => ['nullable', 'numeric', 'min:0'],
            'plans.*.video_quota' => ['nullable'],
            'plans.*.duration_days' => ['nullable', 'integer', 'min:0'],
            'plans.*.is_active' => ['nullable'],
            'price_monthly' => ['nullable', 'numeric', 'min:0'],
            'price_quarterly' => ['nullable', 'numeric', 'min:0'],
            'price_yearly' => ['nullable', 'numeric', 'min:0'],
            'qris_enabled' => ['nullable'],
            'qris_account_name' => ['nullable', 'string', 'max:100'],
            'qris_raw_payload' => ['nullable', 'string', 'max:1000'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_qris' => ['nullable', 'boolean'],
        ]);

        // Clean and prepare bank accounts
        $cleanBankAccounts = [];
        foreach ($validated['bank_accounts'] as $b) {
            $bankName = trim((string) ($b['bank_name'] ?? ''));
            $accNumber = trim((string) ($b['account_number'] ?? ''));
            $accName = trim((string) ($b['account_name'] ?? ''));

            $info = BankDirectory::findByCodeOrName($b['bank_code'] ?? $bankName);
            $bankCode = $info['code'] ?? (!empty($b['bank_code']) ? trim($b['bank_code']) : Str::slug($bankName, '_'));

            if (!empty($bankName) && !empty($accNumber) && !empty($accName)) {
                $cleanBankAccounts[] = [
                    'bank_code' => $bankCode,
                    'bank_name' => $bankName,
                    'account_number' => $accNumber,
                    'account_name' => $accName,
                ];
            }
        }

        if (empty($cleanBankAccounts)) {
            return back()->withErrors(['bank_accounts' => 'Setidaknya harus ada satu rekening bank yang valid.']);
        }

        // Save system settings
        SystemSetting::set('free_preview_episodes', (string) $validated['free_preview_episodes'], 'integer');
        SystemSetting::set('official_bank_accounts', $cleanBankAccounts, 'json');

        // Backwards compatibility for single BCA & Mandiri if present
        foreach ($cleanBankAccounts as $b) {
            if (stripos($b['bank_name'], 'bca') !== false) {
                SystemSetting::set('bca_account_number', $b['account_number'], 'text');
                SystemSetting::set('bca_account_name', $b['account_name'], 'text');
            } elseif (stripos($b['bank_name'], 'mandiri') !== false) {
                SystemSetting::set('mandiri_account_number', $b['account_number'], 'text');
                SystemSetting::set('mandiri_account_name', $b['account_name'], 'text');
            }
        }

        // Update dynamic client plans if submitted
        if (!empty($validated['plans']) && is_array($validated['plans'])) {
            foreach ($validated['plans'] as $planCode => $planData) {
                $plan = SubscriptionPlan::where('code', $planCode)->first();
                if ($plan) {
                    $updatePayload = [];
                    if (isset($planData['price']) && $planData['price'] !== '') {
                        $updatePayload['price'] = (float) $planData['price'];
                    }
                    if (array_key_exists('video_quota', $planData)) {
                        $rawQuota = $planData['video_quota'];
                        $updatePayload['video_quota'] = ($rawQuota !== '' && $rawQuota !== null)
                            ? (int) $rawQuota
                            : null;
                    }
                    if (isset($planData['duration_days']) && $planData['duration_days'] !== '') {
                        $updatePayload['duration_days'] = (int) $planData['duration_days'];
                    }
                    $updatePayload['is_active'] = !empty($planData['is_active']);

                    $plan->update($updatePayload);
                }
            }
        }

        // Backward compatibility for legacy plan price keys
        if (isset($validated['price_monthly'])) {
            SubscriptionPlan::where('code', 'monthly')->update(['price' => $validated['price_monthly']]);
        }
        if (isset($validated['price_quarterly'])) {
            SubscriptionPlan::where('code', 'quarterly')->update(['price' => $validated['price_quarterly']]);
        }
        if (isset($validated['price_yearly'])) {
            SubscriptionPlan::where('code', 'yearly')->update(['price' => $validated['price_yearly']]);
        }

        // QRIS Switch and settings (persists even when switched off)
        $qrisEnabled = $request->boolean('qris_enabled');
        SystemSetting::set('qris_enabled', $qrisEnabled ? 'true' : 'false', 'boolean');

        if ($request->filled('qris_account_name')) {
            SystemSetting::set('qris_account_name', trim((string) $request->input('qris_account_name')), 'text');
        }

        if ($request->has('qris_raw_payload') && $request->filled('qris_raw_payload')) {
            SystemSetting::set('qris_raw_payload', trim((string) $request->input('qris_raw_payload')), 'text');
        }

        // Handle QRIS Image Upload or Removal
        $currentQris = SystemSetting::get('qris_official_image', '');
        $statusMsg = 'Pengaturan sistem & konfigurasi pembayaran resmi berhasil diperbarui.';

        if ($request->boolean('remove_qris')) {
            if (!empty($currentQris)) {
                $this->fileStorage->deleteFile('system', $currentQris);
                SystemSetting::set('qris_official_image', '', 'text');
                SystemSetting::set('qris_raw_payload', '', 'text');
            }
        } elseif ($request->hasFile('qris_image')) {
            $uploadedImage = $request->file('qris_image');
            $tempPath = $uploadedImage->getRealPath();

            // Auto-decode QR code from image
            $qrisService = app(\App\Services\QrisService::class);
            $decodedText = $qrisService->decodeImage($tempPath);

            $newQris = $this->fileStorage->storeUploadedImageAsWebp(
                $uploadedImage,
                'system',
                'qris_official',
                90,
                5242880
            );

            if (!empty($currentQris)) {
                $this->fileStorage->deleteFile('system', $currentQris);
            }

            SystemSetting::set('qris_official_image', $newQris, 'text');

            if (!empty($decodedText)) {
                SystemSetting::set('qris_raw_payload', $decodedText, 'text');
                $merchantInfo = $qrisService->parseMerchantInfo($decodedText);
                if (!empty($merchantInfo['merchant_name'])) {
                    SystemSetting::set('qris_account_name', $merchantInfo['merchant_name'], 'text');
                }
                $statusMsg = "Pengaturan berhasil diperbarui. Gambar QRIS berhasil diunggah dan kode berhasil dibaca otomatis ({$merchantInfo['merchant_name']}).";
            } else {
                $statusMsg = "Pengaturan berhasil diperbarui. Gambar QRIS berhasil diunggah (Data QRIS sebelumnya tetap tersimpan).";
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', $statusMsg);
    }
}
