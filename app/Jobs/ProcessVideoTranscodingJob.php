<?php

namespace App\Jobs;

use App\Models\Episode;
use App\Services\FileStorageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessVideoTranscodingJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $episodeId,
        public string $rawVideoFilename
    ) {}

    /**
     * Execute the job.
     */
    public function handle(FileStorageService $fileStorage): void
    {
        $episode = Episode::find($this->episodeId);
        if (! $episode) {
            return;
        }

        try {
            $transcodedFilename = $fileStorage->transcodeStoredVideoToWebm(
                $this->rawVideoFilename,
                'videos',
                'vid_ep'.$episode->episode_number
            );

            if ($transcodedFilename && $transcodedFilename !== $this->rawVideoFilename) {
                $episode->update(['video_file' => $transcodedFilename]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to transcode video for episode {$this->episodeId}: {$e->getMessage()}", [
                'exception' => $e,
            ]);
        }
    }
}
