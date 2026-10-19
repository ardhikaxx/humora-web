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
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1));
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
            if (!$this->valid_until || Carbon::now()->greaterThan($this->valid_until)) {
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
     * Remaining days of VIP subscription
     */
    public function getRemainingDaysAttribute(): int
    {
        if (!$this->hasActiveVip() || !$this->valid_until) {
            return 0;
        }

        $diff = Carbon::now()->diffInDays($this->valid_until, false);
        return max(0, (int) ceil($diff));
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
