<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'series_id' => $this->series_id,
            'series_title' => $this->series ? $this->series->title : '',
            'episode_id' => $this->episode_id,
            'episode_number' => $this->episode ? $this->episode->episode_number : 1,
            'episode_title' => $this->episode ? $this->episode->title : '',
            'thumbnail_url' => $this->series ? $this->series->thumbnail_url : '',
            'last_position' => (int) $this->last_position,
            'progress_percent' => (int) $this->progress_percent,
            'is_completed' => (bool) $this->is_completed,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
