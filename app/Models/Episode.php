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

    protected static function booted(): void
    {
        static::creating(function (Episode $episode) {
            if (empty($episode->access_type)) {
                $episode->access_type = 'free';
            }
        });
    }

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
     * Determine whether this episode is free or premium based on admin setting.
     */
    public function isFreePreview(): bool
    {
        return ($this->access_type ?? 'free') === 'free';
    }

    public function getIsFreeAttribute(): bool
    {
        return $this->isFreePreview();
    }

    public function getIsPremiumAttribute(): bool
    {
        return ! $this->isFreePreview();
    }

    public function getLikesCountAttribute(): int
    {
        if (array_key_exists('likes_count', $this->attributes)) {
            return (int) $this->attributes['likes_count'];
        }

        return $this->relationLoaded('likes')
            ? $this->likes->count()
            : $this->likes()->count();
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

        if (! $user) {
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
        if ($user->hasActiveVip() && ! $user->userVideoAccesses()->exists()) {
            return true;
        }

        return false;
    }
}
