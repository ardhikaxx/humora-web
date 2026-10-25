<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Episode;
use App\Models\Series;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LandingController extends Controller
{
    /**
     * Display the public landing page introducing HUMORA Mobile.
     */
    public function index()
    {
        $featuredSeries = Series::with(['categories'])
            ->withCount(['publishedEpisodes', 'episodes'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->first();

        if (! $featuredSeries) {
            $featuredSeries = Series::with(['categories'])
                ->withCount(['publishedEpisodes', 'episodes'])
                ->where('status', 'published')
                ->first();
        }

        $seriesList = Series::with(['categories'])
            ->withCount(['publishedEpisodes', 'episodes'])
            ->where('status', 'published')
            ->orderBy('catalog_order')
            ->get();

        $categories = Category::where('is_active', true)
            ->whereHas('series', function ($q) {
                $q->where('status', 'published');
            })
            ->get();

        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->get();

        $stats = [
            'total_series' => Series::where('status', 'published')->count(),
            'total_episodes' => Episode::where('status', 'published')->count(),
            'free_episodes_per_series' => 2,
        ];

        // Check for APK files inside storage/uploads/mobile
        $mobileDir = storage_path('uploads/mobile');
        $apkFiles = is_dir($mobileDir) ? glob($mobileDir.DIRECTORY_SEPARATOR.'*.apk') : [];
        $apkAvailable = ! empty($apkFiles);
        $apkSize = null;
        $apkFilename = null;

        if ($apkAvailable) {
            usort($apkFiles, fn ($a, $b) => filemtime($b) <=> filemtime($a));
            $latestApk = $apkFiles[0];
            $apkFilename = basename($latestApk);
            $apkSize = round(filesize($latestApk) / (1024 * 1024), 1).' MB';
        }

        // Fetch thumbnails from public/images/thumbnails for the hero grid and experience section
        $thumbDir = public_path('images/thumbnails');
        $heroThumbnails = [];
        if (is_dir($thumbDir)) {
            $files = glob($thumbDir.DIRECTORY_SEPARATOR.'*.{webp,jpg,jpeg,png}', GLOB_BRACE);
            $heroThumbnails = array_map(fn ($f) => asset('images/thumbnails/'.basename($f)), $files);
        }

        return view('landing', compact('featuredSeries', 'seriesList', 'categories', 'plans', 'stats', 'apkAvailable', 'apkSize', 'apkFilename', 'heroThumbnails'));
    }

    /**
     * Download the latest HUMORA Mobile Android APK from storage/uploads/mobile.
     */
    public function downloadApk(): BinaryFileResponse|RedirectResponse
    {
        $mobileDir = storage_path('uploads/mobile');
        $apkFiles = is_dir($mobileDir) ? glob($mobileDir.DIRECTORY_SEPARATOR.'*.apk') : [];

        if (empty($apkFiles)) {
            return redirect()->route('landing')
                ->with('apk_not_found', 'Berkas APK HUMORA Mobile saat ini belum diunggah di folder: storage/uploads/mobile. Silakan tempatkan file .apk Anda di folder tersebut agar dapat diunduh.');
        }

        // Sort descending by modified time (latest file first)
        usort($apkFiles, fn ($a, $b) => filemtime($b) <=> filemtime($a));
        $targetFile = $apkFiles[0];

        return response()->download($targetFile, basename($targetFile), [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }

    /**
     * Generate dynamic sitemap.xml for Googlebot and search engines.
     */
    public function sitemap(): Response
    {
        $series = Series::where('status', 'published')
            ->select('id', 'slug', 'updated_at')
            ->orderBy('catalog_order')
            ->get();

        $xml = view('sitemap', compact('series'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
