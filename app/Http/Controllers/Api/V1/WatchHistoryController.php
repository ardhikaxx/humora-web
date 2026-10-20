<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\WatchHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    /**
     * Get user's watch history list
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $history = WatchHistory::where('user_id', $user->id)
            ->with(['series', 'episode'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (WatchHistory $item) {
                return [
                    'id' => $item->id,
                    'series_id' => $item->series_id,
                    'series_title' => $item->series ? $item->series->title : '',
                    'episode_id' => $item->episode_id,
                    'episode_number' => $item->episode ? $item->episode->episode_number : 1,
                    'episode_title' => $item->episode ? $item->episode->title : '',
                    'thumbnail_url' => $item->series ? $item->series->thumbnail_url : '',
                    'last_position' => $item->last_position,
                    'progress_percent' => $item->progress_percent,
                    'is_completed' => $item->is_completed,
                    'updated_at' => $item->updated_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Riwayat tontonan berhasil diambil.',
            'data' => $history,
        ]);
    }

    /**
     * Update playback progress for an episode
     */
    public function updateProgress(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'episode_id' => ['required', 'exists:episodes,id'],
            'series_id' => ['nullable', 'exists:series,id'],
            'last_position' => ['required', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        $episode = Episode::with('series')->findOrFail($validated['episode_id']);
        $seriesId = $validated['series_id'] ?? $episode->series_id;
        $durationSeconds = (int) ($validated['duration_seconds'] ?? ($episode->duration_seconds ?: 120));

        $progressPercent = $validated['progress_percent'] ?? null;
        if ($progressPercent === null) {
            $progressPercent = $durationSeconds > 0
                ? (int) min(100, round(($validated['last_position'] / $durationSeconds) * 100))
                : 0;
        }

        $isCompleted = $progressPercent >= 90;

        $history = WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'episode_id' => $episode->id,
            ],
            [
                'series_id' => $seriesId,
                'last_position' => $validated['last_position'],
                'progress_percent' => $progressPercent,
                'is_completed' => $isCompleted,
            ]
        );

        // Also record/sync to ViewingEvent
        $sessionId = !empty($validated['session_id']) ? $validated['session_id'] : 'hist_' . $user->id . '_' . $episode->id . '_' . date('Ymd');
        $event = \App\Models\ViewingEvent::firstOrNew(['session_id' => $sessionId]);
        if (!$event->exists) {
            $event->episode_id = $episode->id;
            $event->series_id = $seriesId;
            $event->user_id = $user->id;
            $event->ip_address = $request->ip();
            $event->user_agent = substr($request->userAgent() ?? '', 0, 500);
            $event->watched_seconds = $validated['last_position'];
            $event->save();
        } else {
            if ($validated['last_position'] > $event->watched_seconds) {
                $event->watched_seconds = $validated['last_position'];
                $event->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Progres tontonan berhasil disimpan.',
            'data' => [
                'id' => $history->id,
                'last_position' => $history->last_position,
                'progress_percent' => $history->progress_percent,
                'is_completed' => $history->is_completed,
            ],
        ]);
    }

    /**
     * Clear watch history for the user
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($episodeId = $request->input('episode_id')) {
            WatchHistory::where('user_id', $user->id)
                ->where('episode_id', $episodeId)
                ->delete();

            $message = 'Item riwayat tontonan berhasil dihapus.';
        } else {
            WatchHistory::where('user_id', $user->id)->delete();
            $message = 'Seluruh riwayat tontonan berhasil dibersihkan.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
