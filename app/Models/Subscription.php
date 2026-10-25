<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'plan_type',
        'video_quota',
        'transaction_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'video_quota' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function videoAccesses(): HasMany
    {
        return $this->hasMany(UserVideoAccess::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && Carbon::now()->lessThanOrEqualTo($this->end_date);
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

    public function getRemainingDaysAttribute(): int
    {
        if (! $this->isActive()) {
            return 0;
        }

        $diff = Carbon::now()->diffInDays($this->end_date, false);

        return max(0, (int) ceil($diff));
    }
}
