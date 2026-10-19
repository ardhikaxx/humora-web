<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'episode_id',
        'series_id',
        'user_id',
        'ip_address',
        'user_agent',
        'watched_seconds',
        'session_id',
    ];

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
