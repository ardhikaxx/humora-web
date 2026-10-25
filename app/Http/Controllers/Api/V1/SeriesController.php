<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeriesResource;
use App\Models\Like;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    /**
     * Get paginated/filtered series catalog with zero N+1 queries
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $query = Series::where('status', 'published')
            ->with(['categories', 'publishedEpisodes'])
            ->withCount(['likes' => fn ($q) => $q->whereNull('episode_id')])
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

        // Pre-fetch liked IDs once across the entire catalog to prevent N+1 queries
        $likedEpisodeIds = $this->getLikedEpisodeIds($user, $request);
        $likedSeriesIds = $this->getLikedSeriesIds($user, $request);

        $perPage = (int) $request->input('per_page', 0);
        if ($perPage > 0) {
            $paginator = $query->paginate($perPage)->withQueryString();
            $data = $paginator->getCollection()->map(function (Series $series) use ($request, $likedEpisodeIds, $likedSeriesIds) {
                return (new SeriesResource($series))->additional([
                    'liked_episode_ids' => $likedEpisodeIds,
                    'liked_series_ids' => $likedSeriesIds,
                ])->toArray($request);
            });

            return response()->json([
                'success' => true,
                'message' => 'Katalog series berhasil diambil.',
                'data' => $data,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'has_more' => $paginator->hasMorePages(),
                ],
            ]);
        }

        $seriesList = $query->get()->map(function (Series $series) use ($request, $likedEpisodeIds, $likedSeriesIds) {
            return (new SeriesResource($series))->additional([
                'liked_episode_ids' => $likedEpisodeIds,
                'liked_series_ids' => $likedSeriesIds,
            ])->toArray($request);
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
            ->withCount(['likes' => fn ($q) => $q->whereNull('episode_id')])
            ->first();

        if (! $series) {
            return response()->json([
                'success' => false,
                'message' => 'Series tidak ditemukan atau belum dipublikasikan.',
            ], 404);
        }

        // User watch history for this series
        $userHistories = $user
            ? $user->watchHistories()->where('series_id', $series->id)->get()->keyBy('episode_id')
            : collect();

        $likedEpisodeIds = $this->getLikedEpisodeIds($user, $request);
        $likedSeriesIds = $this->getLikedSeriesIds($user, $request);

        $resource = (new SeriesResource($series))->additional([
            'liked_episode_ids' => $likedEpisodeIds,
            'liked_series_ids' => $likedSeriesIds,
            'watch_histories' => $userHistories,
        ])->toArray($request);

        return response()->json([
            'success' => true,
            'message' => 'Detail series berhasil diambil.',
            'data' => $resource,
        ]);
    }

    /**
     * Helper to resolve liked episode IDs for user or guest session
     */
    protected function getLikedEpisodeIds(?User $user, ?Request $request): Collection
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
     * Helper to resolve liked series IDs for user or guest session
     */
    protected function getLikedSeriesIds(?User $user, ?Request $request): Collection
    {
        if ($user) {
            return Like::where('user_id', $user->id)
                ->whereNull('episode_id')
                ->whereNotNull('series_id')
                ->pluck('series_id')
                ->flip();
        }

        $sessionId = $request?->header('X-Session-ID') ?: $request?->header('X-Guest-ID') ?: session()->getId();
        $ip = $request?->ip();

        return Like::whereNull('user_id')
            ->whereNull('episode_id')
            ->whereNotNull('series_id')
            ->where(function ($q) use ($sessionId, $ip) {
                if ($sessionId) {
                    $q->where('session_id', $sessionId);
                } else {
                    $q->where('ip_address', $ip);
                }
            })
            ->pluck('series_id')
            ->flip();
    }
}
