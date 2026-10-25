<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SeriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::guard('sanctum')->user();

        $likedSeriesIds = $this->additional['liked_series_ids'] ?? null;
        if ($likedSeriesIds !== null) {
            $isLiked = isset($likedSeriesIds[$this->id]);
        } else {
            $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID') ?: session()->getId();
            $ip = $request->ip();
            $isLiked = $user
                ? $user->likes()->where('series_id', $this->id)->whereNull('episode_id')->exists()
                : Like::whereNull('user_id')
                    ->where('series_id', $this->id)
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

        $likedEpisodeIds = $this->additional['liked_episode_ids'] ?? null;
        $userHistories = $this->additional['watch_histories'] ?? collect();

        $episodesCollection = $this->relationLoaded('publishedEpisodes')
            ? $this->publishedEpisodes
            : ($this->relationLoaded('episodes') ? $this->episodes : collect());

        $episodes = $episodesCollection->map(function ($ep) use ($user, $likedEpisodeIds, $userHistories) {
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
                'is_free' => (bool) $isFree,
                'is_locked' => ! $isAccessible,
                'is_watched' => $history ? (bool) $history->is_completed : false,
                'watch_progress' => $history ? (float) ($history->progress_percent / 100.0) : 0.0,
                'last_position' => $history ? (int) $history->last_position : 0,
                'thumbnail_url' => $this->thumbnail_url,
                'likes_count' => (int) $ep->likes_count,
                'is_liked' => $likedEpisodeIds !== null ? isset($likedEpisodeIds[$ep->id]) : false,
                'stream_url' => $isAccessible ? url('/api/v1/files/stream/'.$ep->id) : null,
            ];
        });

        $categories = $this->relationLoaded('categories')
            ? $this->categories->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ])
            : collect();

        $genre = $this->relationLoaded('categories')
            ? $this->categories->pluck('name')->implode(', ')
            : ($this->genre ?? 'Komedi');

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'synopsis' => $this->synopsis,
            'release_year' => $this->release_year,
            'thumbnail_url' => $this->thumbnail_url,
            'catalog_order' => $this->catalog_order,
            'is_featured' => (bool) $this->is_featured,
            'is_popular' => true,
            'is_new' => true,
            'is_free_available' => true,
            'total_episodes' => $episodesCollection->count(),
            'is_liked' => (bool) $isLiked,
            'likes_count' => (int) $this->likes_count,
            'categories' => $categories,
            'genre' => $genre,
            'episodes' => $episodes,
        ];
    }
}
