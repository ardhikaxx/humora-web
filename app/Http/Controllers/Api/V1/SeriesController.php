<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Like;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    /**
     * Get paginated/filtered series catalog
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $query = Series::where('status', 'published')
            ->with(['categories', 'publishedEpisodes'])
            ->orderBy('catalog_order', 'asc')
            ->orderBy('id', 'asc');

        // Search filter
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($catSlug = $request->input('category')) {
            $query->whereHas('categories', function ($cq) use ($catSlug) {
                $cq->where('slug', $catSlug);
            });
        }

        // Featured only filter (for Spotlight hero banner)
        if ($request->boolean('is_featured')) {
            $query->where('is_featured', true);
        }

        $seriesList = $query->get()->map(function (Series $series) use ($user, $request) {
            return $this->formatSeriesSummary($series, $user, $request);
        });

        return response()->json([
            'success' => true,
            'message' => 'Katalog series berhasil diambil.',
            'data' => $seriesList,
        ]);
    }

    /**
     * Get detailed series by slug or ID with full episode list and access flags
     */
    public function show(Request $request, string $slugOrId): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $series = Series::where('status', 'published')
            ->where(function ($q) use ($slugOrId) {
                $q->where('slug', $slugOrId)
                    ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : -1);
            })
            ->with(['categories', 'publishedEpisodes'])
            ->first();

        if (!$series) {
            return response()->json([
                'success' => false,
                'message' => 'Series tidak ditemukan atau belum dipublikasikan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail series berhasil diambil.',
            'data' => $this->formatSeriesDetail($series, $user, $request),
        ]);
    }

    /**
     * Helper to resolve liked episode IDs for user or guest session
     */
    protected function getLikedEpisodeIds(?User $user, ?Request $request): \Illuminate\Support\Collection
    {
        if ($user) {
            return Like::where('user_id', $user->id)
                ->whereNotNull('episode_id')
                ->pluck('episode_id')
                ->flip();
        }

        $sessionId = $request?->header('X-Session-ID') ?: $request?->header('X-Guest-ID') ?: session()->getId();
        $ip = $request?->ip();

        return Like::whereNull('user_id')
            ->whereNotNull('episode_id')
            ->where(function ($q) use ($sessionId, $ip) {
                if ($sessionId) {
                    $q->where('session_id', $sessionId);
                } else {
                    $q->where('ip_address', $ip);
                }
            })
            ->pluck('episode_id')
            ->flip();
    }

    /**
     * Helper to check if series is liked by user or guest session
     */
    protected function isSeriesLiked(int $seriesId, ?User $user, ?Request $request): bool
    {
        if ($user) {
            return Like::where('user_id', $user->id)
                ->where('series_id', $seriesId)
                ->whereNull('episode_id')
                ->exists();
        }

        $sessionId = $request?->header('X-Session-ID') ?: $request?->header('X-Guest-ID') ?: session()->getId();
        $ip = $request?->ip();

        return Like::whereNull('user_id')
            ->where('series_id', $seriesId)
            ->whereNull('episode_id')
            ->where(function ($q) use ($sessionId, $ip) {
                if ($sessionId) {
                    $q->where('session_id', $sessionId);
                } else {
                    $q->where('ip_address', $ip);
                }
            })
            ->exists();
    }

    /**
     * Format series summary for catalog lists
     */
    protected function formatSeriesSummary(Series $series, ?User $user, ?Request $request = null): array
    {
        $likedEpisodeIds = $this->getLikedEpisodeIds($user, $request);
        $isSeriesLiked = $this->isSeriesLiked($series->id, $user, $request);

        $episodes = $series->publishedEpisodes->map(function ($ep) use ($user, $series, $likedEpisodeIds) {
            $isFree = $ep->isFreePreview();
            $isAccessible = $ep->isAccessibleBy($user);

            return [
                'id' => $ep->id,
                'series_id' => $ep->series_id,
                'episode_number' => $ep->episode_number,
                'title' => $ep->title,
                'synopsis' => $ep->synopsis,
                'duration' => $ep->duration,
                'duration_seconds' => $ep->duration_seconds,
                'access_type' => $ep->access_type,
                'is_free' => $isFree,
                'is_locked' => !$isAccessible,
                'is_watched' => false,
                'watch_progress' => 0.0,
                'last_position' => 0,
                'thumbnail_url' => $series->thumbnail_url,
                'likes_count' => $ep->likes_count,
                'is_liked' => isset($likedEpisodeIds[$ep->id]),
                'stream_url' => $isAccessible ? url('/api/v1/files/stream/' . $ep->id) : null,
            ];
        });

        return [
            'id' => $series->id,
            'slug' => $series->slug,
            'title' => $series->title,
            'synopsis' => $series->synopsis,
            'release_year' => $series->release_year,
            'thumbnail_url' => $series->thumbnail_url,
            'catalog_order' => $series->catalog_order,
            'is_featured' => (bool) $series->is_featured,
            'is_popular' => true,
            'is_new' => true,
            'is_free_available' => true,
            'total_episodes' => $series->publishedEpisodes->count(),
            'is_liked' => $isSeriesLiked,
            'likes_count' => $series->likes()->whereNull('episode_id')->count(),
            'categories' => $series->categories->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ]),
            'genre' => $series->categories->pluck('name')->implode(', '),
            'episodes' => $episodes,
        ];
    }

    /**
     * Format detailed series with episodes and viewing permissions
     */
    protected function formatSeriesDetail(Series $series, ?User $user, ?Request $request = null): array
    {
        // User watch history for this series
        $userHistories = $user
            ? $user->watchHistories()->where('series_id', $series->id)->get()->keyBy('episode_id')
            : collect();

        $likedEpisodeIds = $this->getLikedEpisodeIds($user, $request);
        $isSeriesLiked = $this->isSeriesLiked($series->id, $user, $request);

        $episodes = $series->publishedEpisodes->map(function ($ep) use ($user, $userHistories, $series, $likedEpisodeIds) {
            $isFree = $ep->isFreePreview();
            $isAccessible = $ep->isAccessibleBy($user);

            $history = $userHistories->get($ep->id);

            return [
                'id' => $ep->id,
                'series_id' => $ep->series_id,
                'episode_number' => $ep->episode_number,
                'title' => $ep->title,
                'synopsis' => $ep->synopsis,
                'duration' => $ep->duration,
                'duration_seconds' => $ep->duration_seconds,
                'access_type' => $ep->access_type,
                'is_free' => $isFree,
                'is_locked' => !$isAccessible,
                'is_watched' => $history ? $history->is_completed : false,
                'watch_progress' => $history ? ($history->progress_percent / 100.0) : 0.0,
                'last_position' => $history ? $history->last_position : 0,
                'thumbnail_url' => $series->thumbnail_url,
                'likes_count' => $ep->likes_count,
                'is_liked' => isset($likedEpisodeIds[$ep->id]),
                // Only provide stream URL if accessible
                'stream_url' => $isAccessible ? url('/api/v1/files/stream/' . $ep->id) : null,
            ];
        });

        return [
            'id' => $series->id,
            'slug' => $series->slug,
            'title' => $series->title,
            'synopsis' => $series->synopsis,
            'release_year' => $series->release_year,
            'thumbnail_url' => $series->thumbnail_url,
            'catalog_order' => $series->catalog_order,
            'is_featured' => $series->is_featured,
            'is_liked' => $isSeriesLiked,
            'likes_count' => $series->likes()->whereNull('episode_id')->count(),
            'total_episodes' => $episodes->count(),
            'categories' => $series->categories->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ]),
            'genre' => $series->categories->pluck('name')->implode(', '),
            'episodes' => $episodes,
        ];
    }
}
