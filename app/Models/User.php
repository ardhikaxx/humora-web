<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'gender',
        'tier',
        'valid_until',
        'status',
        'status_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'initials',
        'effective_tier',
        'remaining_days',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'valid_until' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Generate initials from name (e.g. Ardhika Pradana -> AP, Admin Humora -> AH)
     */
    public function getInitialsAttribute(): string
    {
        $name = trim((string) ($this->name ?? ''));
        if (empty($name)) {
            return 'HU';
        }

        $parts = preg_split('/\s+/', $name);
        if (count($parts) >= 2) {
            return strtoupper(mb_substr($parts[0], 0, 1).mb_substr($parts[count($parts) - 1], 0, 1));
        }

        return strtoupper(mb_substr($parts[0], 0, min(2, mb_strlen($parts[0]))));
    }

    /**
     * Effective tier calculation:
     * - Returns 'banned' or 'suspended' if status is not active.
     * - If tier is 'premium' but valid_until is past, returns 'expired'.
     * - Otherwise returns stored tier ('free' or 'premium').
     */
    public function getEffectiveTierAttribute(): string
    {
        if ($this->status === 'banned') {
            return 'banned';
        }
        if ($this->status === 'suspended') {
            return 'suspended';
        }

        if ($this->tier === 'premium') {
            if (! $this->valid_until || Carbon::now()->greaterThan($this->valid_until)) {
                return 'expired';
            }

            return 'premium';
        }

        return 'free';
    }

    /**
     * Check if user currently has valid active VIP access
     */
    public function hasActiveVip(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->tier !== 'premium') {
            return false;
        }

        return $this->valid_until && Carbon::now()->lessThanOrEqualTo($this->valid_until);
    }

    /**
     * Check if user currently has active Full Access to entire catalog
     */
    public function hasActiveFullAccess(): bool
    {
        if (! $this->hasActiveVip()) {
            return false;
        }

        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::now())
            ->where(function ($q) {
                $q->where('plan_type', 'full')
                    ->orWhereHas('plan', fn ($pq) => $pq->where('plan_type', 'full'));
            })
            ->exists();
    }

    /**
     * Check if user currently has active Weekly Subscription
     */
    public function hasActiveWeeklySubscription(): bool
    {
        if (! $this->hasActiveVip()) {
            return false;
        }

        if ($this->hasActiveFullAccess()) {
            return false;
        }

        $weekly = $this->currentWeeklySubscription();
        if (! $weekly) {
            return false;
        }

        return $this->remainingVideoQuota() > 0;
    }

    /**
     * Get the latest weekly subscription for the current cycle
     */
    public function currentWeeklySubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->with('plan')
            ->where(function ($q) {
                $q->where('plan_type', 'weekly')
                    ->orWhereHas('plan', fn ($pq) => $pq->where('plan_type', 'weekly'));
            })
            ->latest('id')
            ->first();
    }

    /**
     * Get the currently active weekly subscription (if any)
     */
    public function activeWeeklySubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::now())
            ->where(function ($q) {
                $q->where('plan_type', 'weekly')
                    ->orWhereHas('plan', fn ($pq) => $pq->where('plan_type', 'weekly'));
            })
            ->latest('end_date')
            ->first();
    }

    /**
     * User can purchase Add-on if they have an active or unexpired Weekly package period
     */
    public function canPurchaseAddon(): bool
    {
        $weekly = $this->currentWeeklySubscription();
        if (! $weekly) {
            return false;
        }

        return $weekly->end_date && Carbon::now()->lessThanOrEqualTo($weekly->end_date);
    }

    /**
     * Total video quota available across the current weekly package and its add-ons
     */
    public function totalVideoQuota(): int
    {
        if ($this->hasActiveFullAccess()) {
            return 0;
        }

        $weekly = $this->currentWeeklySubscription();
        if (! $weekly) {
            return 0;
        }

        $weeklyQuota = (int) ($weekly->video_quota ?: 15);

        $addonQuota = (int) $this->subscriptions()
            ->where('plan_type', 'addon')
            ->where('created_at', '>=', $weekly->created_at)
            ->sum('video_quota');

        return $weeklyQuota + $addonQuota;
    }

    /**
     * Count of distinct episodes unlocked in the current weekly cycle
     */
    public function usedVideoQuota(): int
    {
        $weekly = $this->currentWeeklySubscription();
        if (! $weekly) {
            return 0;
        }

        $addonIds = $this->subscriptions()
            ->where('plan_type', 'addon')
            ->where('created_at', '>=', $weekly->created_at)
            ->pluck('id')
            ->all();

        $cycleSubIds = array_merge([$weekly->id], $addonIds);

        return $this->userVideoAccesses()
            ->where(function ($q) use ($cycleSubIds, $weekly) {
                $q->whereIn('subscription_id', $cycleSubIds)
                    ->orWhere('created_at', '>=', $weekly->created_at);
            })
            ->distinct('episode_id')
            ->count('episode_id');
    }

    /**
     * Remaining video quota available in the current cycle
     */
    public function remainingVideoQuota(): int
    {
        if ($this->hasActiveFullAccess()) {
            return 0;
        }

        $weekly = $this->currentWeeklySubscription();
        if (! $weekly) {
            return 0;
        }

        $remaining = $this->totalVideoQuota() - $this->usedVideoQuota();

        return max(0, $remaining);
    }

    /**
     * Unlock an episode using available quota
     */
    public function unlockEpisodeWithQuota(int $episodeId): bool
    {
        if ($this->hasActiveFullAccess()) {
            return true;
        }

        if ($this->hasActiveAccessToEpisode($episodeId)) {
            return true;
        }

        if (! $this->hasActiveVip() || $this->remainingVideoQuota() <= 0) {
            return false;
        }

        $weekly = $this->currentWeeklySubscription();
        if (! $weekly) {
            return false;
        }

        $activeSub = $this->subscriptions()
            ->whereIn('plan_type', ['weekly', 'addon'])
            ->where('status', 'active')
            ->latest('id')
            ->first() ?? $weekly;

        $expiresAt = $this->valid_until && $this->valid_until->isFuture()
            ? $this->valid_until
            : ($weekly->end_date && $weekly->end_date->isFuture() ? $weekly->end_date : Carbon::now()->addDays(7));

        UserVideoAccess::create([
            'user_id' => $this->id,
            'episode_id' => $episodeId,
            'subscription_id' => $activeSub->id,
            'transaction_id' => $activeSub->transaction_id,
            'granted_at' => Carbon::now(),
            'expires_at' => $expiresAt,
        ]);

        // Check if quota has reached 0 after this unlock
        $remaining = $this->remainingVideoQuota();
        if ($remaining <= 0) {
            $this->update([
                'tier' => 'free',
                'valid_until' => Carbon::now(),
            ]);

            $this->subscriptions()
                ->whereIn('plan_type', ['weekly', 'addon'])
                ->where('status', 'active')
                ->update([
                    'status' => 'expired',
                ]);
        }

        return true;
    }

    /**
     * Check if user currently has active access to a specific episode
     */
    public function hasActiveAccessToEpisode(int $episodeId): bool
    {
        if ($this->hasActiveFullAccess()) {
            return true;
        }

        return $this->userVideoAccesses()
            ->where('episode_id', $episodeId)
            ->where('expires_at', '>=', Carbon::now())
            ->exists();
    }

    /**
     * Get list of all episode IDs currently accessible by user
     */
    public function activeAccessibleEpisodeIds(): array
    {
        if ($this->hasActiveFullAccess()) {
            return Episode::where('status', 'published')->pluck('id')->all();
        }

        return $this->userVideoAccesses()
            ->where('expires_at', '>=', Carbon::now())
            ->pluck('episode_id')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get list of all episode IDs historically granted to this user (prevents repeats)
     */
    public function allHistoricallyGrantedEpisodeIds(): array
    {
        return $this->userVideoAccesses()
            ->pluck('episode_id')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Remaining days of VIP subscription
     */
    public function getRemainingDaysAttribute(): int
    {
        if (! $this->hasActiveVip() || ! $this->valid_until) {
            return 0;
        }

        $diff = Carbon::now()->diffInDays($this->valid_until, false);

        return max(0, (int) ceil($diff));
    }

    public function userVideoAccesses(): HasMany
    {
        return $this->hasMany(UserVideoAccess::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function watchHistories(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
