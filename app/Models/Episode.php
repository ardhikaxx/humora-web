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

    /**
     * Policy: Episodes 1 & 2 are ALWAYS FREE preview episodes.
     */
    public function isFreePreview(): bool
    {
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
        // Episodes 1 and 2 of published series are free for everyone
        if ($this->isFreePreview()) {
            return true;
        }

        // Episode 3 and above requires an authenticated user with an active VIP subscription
        if (!$user) {
            return false;
        }

        return $user->hasActiveVip();
    }
}
