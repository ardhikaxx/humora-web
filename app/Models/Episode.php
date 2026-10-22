<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'series_id',
        'episode_number',
        'title',
        'synopsis',
        'duration',
        'duration_seconds',
        'video_file',
        'access_type',
        'status',
    ];

    protected $casts = [
        'episode_number' => 'integer',
        'duration_seconds' => 'integer',
    ];

    protected $appends = [
        'is_free',
        'is_premium',
        'likes_count',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function watchHistories(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function viewingEvents(): HasMany
    {
        return $this->hasMany(ViewingEvent::class);
    }

    public function userVideoAccesses(): HasMany
    {
        return $this->hasMany(UserVideoAccess::class);
    }

    /**
     * Free trial videos policy:
     * - If a series has <= 2 total published episodes, only Episode 1 is free trial (Episode 2 is paid).
     * - If a series has > 2 published episodes, Episode 1 & 2 are free trial (Episode 3+ are paid).
     */
    public function isFreePreview(): bool
    {
        $series = $this->relationLoaded('series') ? $this->series : $this->series()->first();
        if ($series) {
            $totalCount = $series->relationLoaded('publishedEpisodes')
                ? $series->publishedEpisodes->count()
                : ($series->published_episodes_count ?? $series->publishedEpisodes()->count());

            if ($totalCount <= 2) {
                return $this->episode_number <= 1;
            }
        }

        return $this->episode_number <= 2;
    }

    public function getIsFreeAttribute(): bool
    {
        return $this->isFreePreview();
    }

    public function getIsPremiumAttribute(): bool
    {
        return !$this->isFreePreview();
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    /**
     * Determines whether a given user (or guest if null) has permission to watch this episode.
     */
    public function isAccessibleBy(?User $user): bool
    {
        // Free preview episodes are accessible by everyone (guest, free, expired)
        if ($this->isFreePreview()) {
            return true;
        }

        if (!$user) {
            return false;
        }

        // Account must be active
        if ($user->status !== 'active') {
            return false;
        }

        // 1. User has active Full Access (seluruh katalog)?
        if ($user->hasActiveFullAccess()) {
            return true;
        }

        // 2. User has an active grant for this specific episode (weekly or add-on)?
        if ($user->hasActiveAccessToEpisode($this->id)) {
            return true;
        }

        // Fallback for legacy users before quota system:
        if ($user->hasActiveVip() && !$user->userVideoAccesses()->exists()) {
            return true;
        }

        return false;
    }
}
