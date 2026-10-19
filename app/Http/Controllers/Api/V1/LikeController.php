<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Like;
use App\Models\Series;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like on a series
     */
    public function toggleSeriesLike(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $series = Series::findOrFail($id);

        $existing = Like::where('user_id', $user->id)
            ->where('series_id', $series->id)
            ->whereNull('episode_id')
            ->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'series_id' => $series->id,
                'episode_id' => null,
            ]);
            $isLiked = true;
        }

        $likesCount = Like::where('series_id', $series->id)
            ->whereNull('episode_id')
            ->count();

        return response()->json([
            'success' => true,
            'message' => $isLiked ? 'Series disukai.' : 'Batal menyukai series.',
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $likesCount,
            ],
        ]);
    }

    /**
     * Toggle like on an episode
     */
    public function toggleEpisodeLike(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $episode = Episode::findOrFail($id);

        $existing = Like::where('user_id', $user->id)
            ->where('episode_id', $episode->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'series_id' => $episode->series_id,
                'episode_id' => $episode->id,
            ]);
            $isLiked = true;
        }

        $likesCount = Like::where('episode_id', $episode->id)->count();

        return response()->json([
            'success' => true,
            'message' => $isLiked ? 'Episode disukai.' : 'Batal menyukai episode.',
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $likesCount,
            ],
        ]);
    }
}
