<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Like;
use App\Models\Series;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like on a series (supports both authenticated users and guests)
     */
    public function toggleSeriesLike(Request $request, int $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user() ?: $request->user();
        $series = Series::findOrFail($id);
        $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID') ?: session()->getId();
        $ip = $request->ip();

        if ($user) {
            $existing = Like::where('user_id', $user->id)
                ->where('series_id', $series->id)
                ->whereNull('episode_id')
                ->first();
        } else {
            $existing = Like::whereNull('user_id')
                ->where('series_id', $series->id)
                ->whereNull('episode_id')
                ->where(function ($q) use ($sessionId, $ip) {
                    if ($sessionId) {
                        $q->where('session_id', $sessionId);
                    } else {
                        $q->where('ip_address', $ip);
                    }
                })
                ->first();
        }

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user?->id,
                'session_id' => $user ? null : $sessionId,
                'ip_address' => $ip,
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
     * Toggle like on an episode (supports both authenticated users and guests)
     */
    public function toggleEpisodeLike(Request $request, int $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user() ?: $request->user();
        $episode = Episode::findOrFail($id);
        $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID') ?: session()->getId();
        $ip = $request->ip();

        if ($user) {
            $existing = Like::where('user_id', $user->id)
                ->where('episode_id', $episode->id)
                ->first();
        } else {
            $existing = Like::whereNull('user_id')
                ->where('episode_id', $episode->id)
                ->where(function ($q) use ($sessionId, $ip) {
                    if ($sessionId) {
                        $q->where('session_id', $sessionId);
                    } else {
                        $q->where('ip_address', $ip);
                    }
                })
                ->first();
        }

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user?->id,
                'session_id' => $user ? null : $sessionId,
                'ip_address' => $ip,
                'series_id' => null,
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

    /**
     * Get current like status and real-time count for an episode
     */
    public function episodeLikeStatus(Request $request, int $id): JsonResponse
    {
        $user = Auth::guard('sanctum')->user() ?: $request->user();
        $episode = Episode::findOrFail($id);
        $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID') ?: session()->getId();
        $ip = $request->ip();

        if ($user) {
            $isLiked = Like::where('user_id', $user->id)
                ->where('episode_id', $episode->id)
                ->exists();
        } else {
            $isLiked = Like::whereNull('user_id')
                ->where('episode_id', $episode->id)
                ->where(function ($q) use ($sessionId, $ip) {
                    if ($sessionId) {
                        $q->where('session_id', $sessionId);
                    } else {
                        $q->where('ip_address', $ip);
                    }
                })
                ->exists();
        }

        $likesCount = Like::where('episode_id', $episode->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'episode_id' => $episode->id,
                'is_liked' => $isLiked,
                'likes_count' => $likesCount,
            ],
        ]);
    }
}
