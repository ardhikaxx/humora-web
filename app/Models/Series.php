<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'synopsis',
        'release_year',
        'thumbnail',
        'catalog_order',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'catalog_order' => 'integer',
    ];

    protected $appends = [
        'thumbnail_url',
        'total_episodes',
        'likes_count',
        'genre',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_series');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number');
    }

    public function publishedEpisodes(): HasMany
    {
        return $this->hasMany(Episode::class)->where('status', 'published')->orderBy('episode_number');
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

    public function getThumbnailUrlAttribute(): string
    {
        if (empty($this->thumbnail)) {
            return url('images/thumbnails/default.jpg');
        }

        // Return the secure, non-symlink file route
        return url('/api/v1/files/thumbnail/'.$this->thumbnail);
    }

    public function getTotalEpisodesAttribute(): int
    {
        return $this->publishedEpisodes()->count();
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

    public function getGenreAttribute(): string
    {
        return $this->relationLoaded('categories')
            ? $this->categories->pluck('name')->implode(', ')
            : $this->categories()->pluck('name')->implode(', ');
    }

    public function getPublishedEpisodesCountAttribute(): int
    {
        return $this->relationLoaded('publishedEpisodes')
            ? $this->publishedEpisodes->count()
            : $this->publishedEpisodes()->count();
    }
}
