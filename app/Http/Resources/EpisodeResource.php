<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::guard('sanctum')->user();
        $isFree = $this->isFreePreview();
        $isAccessible = $this->isAccessibleBy($user);

        // Pre-computed context or computed fallback
        $isLiked = $this->additional['is_liked'] ?? null;
        if ($isLiked === null) {
            $likedIds = $this->additional['liked_episode_ids'] ?? null;
            if ($likedIds !== null) {
                $isLiked = isset($likedIds[$this->id]);
            } else {
                $sessionId = $request->header('X-Session-ID') ?: $request->header('X-Guest-ID') ?: session()->getId();
                $ip = $request->ip();
                $isLiked = $user
                    ? $user->likes()->where('episode_id', $this->id)->exists()
                    : Like::whereNull('user_id')
                        ->where('episode_id', $this->id)
                        ->where(function ($q) use ($sessionId, $ip) {
                            if ($sessionId) {
                                $q->where('session_id', $sessionId);
                            } else {
                                $q->where('ip_address', $ip);
                            }
                        })
                        ->exists();
            }
        }

        $seriesThumbnail = ($this->relationLoaded('series') && $this->series)
            ? $this->series->thumbnail_url
            : ($this->thumbnail_url ?? asset('images/default-img.png'));

        return [
            'id' => $this->id,
            'series_id' => $this->series_id,
            'series_title' => $this->relationLoaded('series') && $this->series ? $this->series->title : ($this->additional['series_title'] ?? ''),
            'episode_number' => $this->episode_number,
            'title' => $this->title,
            'synopsis' => $this->synopsis ?? '',
            'duration' => $this->duration,
            'duration_seconds' => $this->duration_seconds,
            'access_type' => $this->access_type,
            'is_free' => (bool) $isFree,
            'is_locked' => ! $isAccessible,
            'is_watched' => (bool) ($this->additional['is_watched'] ?? false),
            'watch_progress' => (float) ($this->additional['watch_progress'] ?? 0.0),
            'last_position' => (int) ($this->additional['last_position'] ?? 0),
            'thumbnail_url' => $seriesThumbnail,
            'likes_count' => (int) $this->likes_count,
            'is_liked' => (bool) $isLiked,
            'stream_url' => $isAccessible ? url('/api/v1/files/stream/'.$this->id) : null,
        ];
    }
}
