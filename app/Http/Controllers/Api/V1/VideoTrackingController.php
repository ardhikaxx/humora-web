<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\ViewingEvent;
use App\Models\WatchHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoTrackingController extends Controller
{
    /**
     * Record or update a viewing event and watch time from mobile or web clients.
     * Accessible by both authenticated users (Sanctum) and guest users.
     */
    public function track(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'episode_id' => ['required', 'integer', 'exists:episodes,id'],
            'session_id' => ['nullable', 'string', 'max:100'],
            'watched_seconds' => ['nullable', 'integer', 'min:0'],
            'last_position' => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $episode = Episode::with('series')->findOrFail($validated['episode_id']);
        $seriesId = $episode->series_id;

        // Extract user from Sanctum token if present
        $user = Auth::guard('sanctum')->user() ?? $request->user('sanctum');
        $userId = $user?->id;

        // Session ID for this continuous playback event
        $sessionId = ! empty($validated['session_id']) ? $validated['session_id'] : Str::uuid()->toString();

        $watchedSeconds = max(0, (int) ($validated['watched_seconds'] ?? 0));
        $lastPosition = max(0, (int) ($validated['last_position'] ?? 0));
        $durationSeconds = max(0, (int) ($validated['duration_seconds'] ?? ($episode->duration_seconds ?: 120)));

        // 1. Record or update ViewingEvent
        $event = ViewingEvent::firstOrNew(['session_id' => $sessionId]);
        $isNew = ! $event->exists;

        if ($isNew) {
            $event->episode_id = $episode->id;
            $event->series_id = $seriesId;
            $event->user_id = $userId;
            $event->ip_address = $request->ip();
            $event->user_agent = substr($request->userAgent() ?? '', 0, 500);
            $event->watched_seconds = $watchedSeconds;
            $event->save();
        } else {
            // Never decrease watched seconds in the same session
            if ($watchedSeconds > $event->watched_seconds) {
                $event->watched_seconds = $watchedSeconds;
            }
            if (! $event->user_id && $userId) {
                $event->user_id = $userId;
            }
            $event->save();
        }

        // 2. If user is authenticated, also sync WatchHistory
        $historyData = null;
        if ($userId) {
            $progressPercent = $validated['progress_percent'] ?? null;
            if ($progressPercent === null) {
                $progressPercent = $durationSeconds > 0
                    ? (int) min(100, round(($lastPosition / $durationSeconds) * 100))
                    : 0;
            }
            $isCompleted = $progressPercent >= 90;

            $history = WatchHistory::updateOrCreate(
                [
                    'user_id' => $userId,
                    'episode_id' => $episode->id,
                ],
                [
                    'series_id' => $seriesId,
                    'last_position' => $lastPosition,
                    'progress_percent' => $progressPercent,
                    'is_completed' => $isCompleted,
                ]
            );

            $historyData = [
                'id' => $history->id,
                'last_position' => $history->last_position,
                'progress_percent' => $history->progress_percent,
                'is_completed' => $history->is_completed,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Progres dan event penayangan berhasil dicatat.',
            'data' => [
                'session_id' => $sessionId,
                'is_new_view' => $isNew,
                'episode_id' => $episode->id,
                'series_id' => $seriesId,
                'watched_seconds' => $event->watched_seconds,
                'watch_history' => $historyData,
            ],
        ]);
    }
}
