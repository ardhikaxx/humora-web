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
            'series_id' => ['required', 'exists:series,id'],
            'episode_id' => ['required', 'exists:episodes,id'],
            'last_position' => ['required', 'integer', 'min:0'],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $isCompleted = $validated['progress_percent'] >= 90;

        $history = WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'episode_id' => $validated['episode_id'],
            ],
            [
                'series_id' => $validated['series_id'],
                'last_position' => $validated['last_position'],
                'progress_percent' => $validated['progress_percent'],
                'is_completed' => $isCompleted,
            ]
        );

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
