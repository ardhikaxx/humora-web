<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\ViewingEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodeController extends Controller
{
    /**
     * Get episode detail with authorization check
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $episode = Episode::with('series')->where('status', 'published')->findOrFail($id);

        if ($episode->series->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Series belum dipublikasikan.',
            ], 404);
        }

        $isFree = $episode->isFreePreview();
        $isAccessible = $episode->isAccessibleBy($user);

        if (!$isAccessible) {
            return response()->json([
                'success' => false,
                'message' => 'Episode ini terkunci dan memerlukan paket langganan Premium VIP aktif.',
                'error_code' => 'PREMIUM_REQUIRED',
                'data' => [
                    'id' => $episode->id,
                    'series_id' => $episode->series_id,
                    'episode_number' => $episode->episode_number,
                    'title' => $episode->title,
                    'is_locked' => true,
                    'is_free' => false,
                ],
            ], 403);
        }

        // Record viewing event deduplicated per session / ip
        $this->recordViewingEvent($episode, $request, $user);

        $watchHistory = $user
            ? $user->watchHistories()->where('episode_id', $episode->id)->first()
            : null;

        $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID') ?: session()->getId();
        $ip = $request->ip();

        $isLiked = $user
            ? $user->likes()->where('episode_id', $episode->id)->exists()
            : \App\Models\Like::whereNull('user_id')
                ->where('episode_id', $episode->id)
                ->where(function ($q) use ($sessionId, $ip) {
                    if ($sessionId) {
                        $q->where('session_id', $sessionId);
                    } else {
                        $q->where('ip_address', $ip);
                    }
                })
                ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Detail episode berhasil diambil.',
            'data' => [
                'id' => $episode->id,
                'series_id' => $episode->series_id,
                'series_title' => $episode->series->title,
                'episode_number' => $episode->episode_number,
                'title' => $episode->title,
                'synopsis' => $episode->synopsis,
                'duration' => $episode->duration,
                'duration_seconds' => $episode->duration_seconds,
                'access_type' => $episode->access_type,
                'is_free' => $isFree,
                'is_locked' => false,
                'last_position' => $watchHistory ? $watchHistory->last_position : 0,
                'progress_percent' => $watchHistory ? $watchHistory->progress_percent : 0,
                'thumbnail_url' => $episode->series->thumbnail_url,
                'likes_count' => $episode->likes_count,
                'is_liked' => $isLiked,
                'stream_url' => url('/api/v1/files/stream/' . $episode->id),
            ],
        ]);
    }

    protected function recordViewingEvent(Episode $episode, Request $request, $user): void
    {
        try {
            $sessionId = $request->header('X-Session-ID', session()->getId());
            $ip = $request->ip();

            // Deduplicate viewing event within last 10 minutes for same episode & user/ip
            $recent = ViewingEvent::where('episode_id', $episode->id)
                ->where(function ($q) use ($user, $ip) {
                    if ($user) {
                        $q->where('user_id', $user->id);
                    } else {
                        $q->where('ip_address', $ip);
                    }
                })
                ->where('created_at', '>=', now()->subMinutes(10))
                ->exists();

            if (!$recent) {
                ViewingEvent::create([
                    'episode_id' => $episode->id,
                    'series_id' => $episode->series_id,
                    'user_id' => $user?->id,
                    'ip_address' => $ip,
                    'user_agent' => substr((string) $request->userAgent(), 0, 500),
                    'session_id' => $sessionId,
                ]);
            }
        } catch (\Throwable $e) {
            // Silently ignore tracking error to keep playback uninterrupted
        }
    }
}
