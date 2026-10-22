<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVideoAccess extends Model
{
    use HasFactory;

    protected $table = 'user_video_accesses';

    protected $fillable = [
        'user_id',
        'episode_id',
        'subscription_id',
        'transaction_id',
        'granted_at',
        'expires_at',
    ];

    protected $casts = [
        'granted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at && $this->expires_at->isFuture();
    }
}
