<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SystemSetting;
use App\Services\FileStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                    'bank_name' => 'Bank Central Asia (BCA)',
                    'account_number' => SystemSetting::get('bca_account_number', '8830-192-888'),
                    'account_name' => SystemSetting::get('bca_account_name', 'PT HUMORA MEDIA KREATIF'),
                ],
                [
                    'bank_name' => 'Bank Mandiri',
                    'account_number' => SystemSetting::get('mandiri_account_number', '137-00-998822-1'),
                    'account_name' => SystemSetting::get('mandiri_account_name', 'HUMORA'),
                ],
            ];
        }
        $qrisImage = SystemSetting::get('qris_official_image', '');

        $plans = SubscriptionPlan::orderBy('duration_days')->get()->keyBy('code');

        return view('admin.settings.index', compact(
            'freeEpisodes',
            'bankAccounts',
            'qrisImage',
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
            'bank_accounts.*.account_number' => ['required', 'string', 'max:50'],
            'bank_accounts.*.account_name' => ['required', 'string', 'max:100'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'price_quarterly' => ['required', 'numeric', 'min:0'],
            'price_yearly' => ['required', 'numeric', 'min:0'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_qris' => ['nullable', 'boolean'],
        ]);

        // Clean and prepare bank accounts
        $cleanBankAccounts = [];
        foreach ($validated['bank_accounts'] as $b) {
            $bankName = trim((string) ($b['bank_name'] ?? ''));
            $accNumber = trim((string) ($b['account_number'] ?? ''));
            $accName = trim((string) ($b['account_name'] ?? ''));

            if (!empty($bankName) && !empty($accNumber) && !empty($accName)) {
                $cleanBankAccounts[] = [
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

        // Update plan prices
        SubscriptionPlan::where('code', 'monthly')->update(['price' => $validated['price_monthly']]);
        SubscriptionPlan::where('code', 'quarterly')->update(['price' => $validated['price_quarterly']]);
        SubscriptionPlan::where('code', 'yearly')->update(['price' => $validated['price_yearly']]);

        // Handle QRIS Image Upload or Removal
        $currentQris = SystemSetting::get('qris_official_image', '');

        if ($request->boolean('remove_qris')) {
            if (!empty($currentQris)) {
                $this->fileStorage->deleteFile('system', $currentQris);
                SystemSetting::set('qris_official_image', '', 'text');
            }
        } elseif ($request->hasFile('qris_image')) {
            $newQris = $this->fileStorage->storeUploadedImageAsWebp(
                $request->file('qris_image'),
                'system',
                'qris_official',
                85,
                5242880
            );

            if (!empty($currentQris)) {
                $this->fileStorage->deleteFile('system', $currentQris);
            }

            SystemSetting::set('qris_official_image', $newQris, 'text');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem & konfigurasi pembayaran resmi berhasil diperbarui.');
    }
}
