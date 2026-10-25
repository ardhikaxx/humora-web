<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FileStorageController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorage
    ) {}

    /**
     * Public stream/view for series thumbnails
     */
    public function publicThumbnail(string $filename): Response
    {
        return $this->fileStorage->streamFileResponse(
            'thumbnails',
            $filename,
            null,
            ['Cache-Control' => 'public, max-age=604800, immutable']
        );
    }

    /**
     * Protected stream/view for transaction proof images (Admin only)
     */
    public function adminProof(string $filename): Response
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin || ! $admin->canManageFinances()) {
            abort(403, 'Akses ke bukti pembayaran tidak diizinkan.');
        }

        return $this->fileStorage->streamFileResponse(
            'proofs',
            $filename,
            null,
            ['Cache-Control' => 'private, no-cache, no-store, must-revalidate']
        );
    }

    /**
     * Public/configured system files such as official QRIS
     */
    public function systemFile(string $filename): Response
    {
        return $this->fileStorage->streamFileResponse(
            'system',
            $filename,
            null,
            ['Cache-Control' => 'public, max-age=604800, immutable']
        );
    }

    /**
     * Secure video streaming endpoint with access control & HTTP Range support
     */
    public function streamEpisodeVideo(Request $request, int $episodeId): Response
    {
        $episode = Episode::with('series')->findOrFail($episodeId);

        // Series and episode must be published
        if ($episode->status !== 'published' || $episode->series->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Konten video belum dipublikasikan.',
                'error_code' => 'CONTENT_NOT_PUBLISHED',
            ], 403);
        }

        // Check authentication for mobile user via Sanctum or session
        $user = Auth::guard('sanctum')->user() ?? Auth::guard('web')->user();

        // Check episode accessibility
        if (! $episode->isAccessibleBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Episode ini terkunci dan memerlukan paket langganan Premium VIP aktif.',
                'error_code' => 'PREMIUM_REQUIRED',
                'data' => [
                    'episode_id' => $episode->id,
                    'episode_number' => $episode->episode_number,
                    'series_id' => $episode->series_id,
                ],
            ], 403);
        }

        if (empty($episode->video_file)) {
            abort(404, 'File video belum diunggah untuk episode ini.');
        }

        $ext = pathinfo($episode->video_file, PATHINFO_EXTENSION) ?: 'webm';

        return $this->fileStorage->streamFileResponse('videos', $episode->video_file, $episode->series->slug.'-ep'.$episode->episode_number.'.'.$ext);
    }
}
