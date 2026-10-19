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
        $bcaNumber = SystemSetting::get('bca_account_number', '8830-192-888');
        $bcaName = SystemSetting::get('bca_account_name', 'PT HUMORA MEDIA KREATIF');
        $mandiriNumber = SystemSetting::get('mandiri_account_number', '137-00-998822-1');
        $mandiriName = SystemSetting::get('mandiri_account_name', 'HUMORA');
        $qrisImage = SystemSetting::get('qris_official_image', '');

        $plans = SubscriptionPlan::orderBy('duration_days')->get()->keyBy('code');

        return view('admin.settings.index', compact(
            'freeEpisodes',
            'bcaNumber',
            'bcaName',
            'mandiriNumber',
            'mandiriName',
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
            return back()->with('error', 'Hanya Super Admin yang berwenang mengubah pengaturan sistem.');
        }

        $validated = $request->validate([
            'free_preview_episodes' => ['required', 'integer', 'min:1', 'max:5'],
            'bca_account_number' => ['required', 'string', 'max:50'],
            'bca_account_name' => ['required', 'string', 'max:100'],
            'mandiri_account_number' => ['required', 'string', 'max:50'],
            'mandiri_account_name' => ['required', 'string', 'max:100'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'price_quarterly' => ['required', 'numeric', 'min:0'],
            'price_yearly' => ['required', 'numeric', 'min:0'],
            'qris_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_qris' => ['nullable', 'boolean'],
        ]);

        // Save system settings
        SystemSetting::set('free_preview_episodes', (string) $validated['free_preview_episodes'], 'integer');
        SystemSetting::set('bca_account_number', $validated['bca_account_number'], 'text');
        SystemSetting::set('bca_account_name', $validated['bca_account_name'], 'text');
        SystemSetting::set('mandiri_account_number', $validated['mandiri_account_number'], 'text');
        SystemSetting::set('mandiri_account_name', $validated['mandiri_account_name'], 'text');

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
