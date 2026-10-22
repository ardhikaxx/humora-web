<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'plan_type',
        'name',
        'duration_days',
        'video_quota',
        'original_price',
        'price',
        'badge_text',
        'benefits',
        'is_active',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'video_quota' => 'integer',
        'original_price' => 'float',
        'price' => 'float',
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'formatted_price',
        'formatted_original_price',
        'formatted_quota',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'plan_id');
    }

    public function isWeekly(): bool
    {
        return $this->plan_type === 'weekly';
    }

    public function isAddon(): bool
    {
        return $this->plan_type === 'addon';
    }

    public function isFullAccess(): bool
    {
        return $this->plan_type === 'full';
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        if ($this->original_price <= 0) {
            return '';
        }
        return 'Rp' . number_format($this->original_price, 0, ',', '.');
    }

    public function getFormattedQuotaAttribute(): string
    {
        if ($this->isFullAccess() || $this->video_quota === null) {
            return 'Seluruh Katalog (~68 Video)';
        }
        return "{$this->video_quota} Video";
    }
}
