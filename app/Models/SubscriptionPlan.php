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
        'name',
        'duration_days',
        'original_price',
        'price',
        'badge_text',
        'benefits',
        'is_active',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'original_price' => 'float',
        'price' => 'float',
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'formatted_price',
        'formatted_original_price',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'plan_id');
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
}
