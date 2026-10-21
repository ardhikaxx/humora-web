<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Series;
use App\Services\FileStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EpisodeController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorage
    ) {}

    /**
     * Display episodes list with series and access filter
     */
    public function index(Request $request): View
    {
        $query = Episode::with('series')->withCount('likes')->orderBy('series_id')->orderBy('episode_number');

        if ($seriesId = $request->input('series_id')) {
            $query->where('series_id', $seriesId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%");
            });
        }

        if ($accessType = $request->input('access_type')) {
            $query->where('access_type', $accessType);
        }

        $episodes = $query->paginate(15)->withQueryString();
        $seriesList = Series::orderBy('title')->get();

        return view('admin.videos.index', compact('episodes', 'seriesList'));
    }

    /**
     * Show create episode form
     */
    public function create(Request $request): View
    {
        $seriesList = Series::orderBy('title')->get();
        $selectedSeriesId = $request->input('series_id', $seriesList->first()?->id);

        // Auto-suggest next episode number for selected series
        $suggestedNumber = 1;
        if ($selectedSeriesId) {
            $lastEp = Episode::where('series_id', $selectedSeriesId)->max('episode_number');
            $suggestedNumber = $lastEp ? $lastEp + 1 : 1;
        }

        $durationPresets = [
            ['label' => '1m 55s', 'minutes' => 1, 'seconds' => 55],
            ['label' => '1m 58s', 'minutes' => 1, 'seconds' => 58],
            ['label' => '2m 00s', 'minutes' => 2, 'seconds' => 0],
            ['label' => '2m 05s', 'minutes' => 2, 'seconds' => 5],
            ['label' => '2m 14s', 'minutes' => 2, 'seconds' => 14],
            ['label' => '2m 20s', 'minutes' => 2, 'seconds' => 20],
        ];

        return view('admin.videos.create', compact('seriesList', 'selectedSeriesId', 'suggestedNumber', 'durationPresets'));
    }

    /**
     * Store new episode
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'series_id' => ['required', 'exists:series,id'],
            'episode_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('episodes')->where(fn ($query) => $query->where('series_id', $request->series_id)),
            ],
            'title' => ['required', 'string', 'max:200'],
            'synopsis' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:0', 'max:60'],
            'duration_seconds' => ['nullable', 'integer', 'min:0', 'max:59'],
            'status' => ['required', 'in:draft,published'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:104857600'], // Max 100MB
        ]);

        $seconds = (int) ($validated['duration_seconds'] ?? 0);
        $totalSeconds = ($validated['duration_minutes'] * 60) + $seconds;
        $formattedDuration = sprintf('%dm %02ds', $validated['duration_minutes'], $seconds);

        // HUMORA Policy: Ep 1 & 2 are ALWAYS FREE preview; Ep 3+ are PREMIUM
        $accessType = $validated['episode_number'] <= 2 ? 'free' : 'premium';

        $videoFilename = 'sample-skit.mp4'; // default fallback
        if ($request->hasFile('video_file')) {
            $videoFilename = $this->fileStorage->storeUploadedVideoAsWebm(
                $request->file('video_file'),
                'videos',
                'vid_ep' . $validated['episode_number']
            );
        }

        $episode = Episode::create([
            'series_id' => $validated['series_id'],
            'episode_number' => $validated['episode_number'],
            'title' => $validated['title'],
            'synopsis' => $validated['synopsis'] ?? null,
            'duration' => $formattedDuration,
            'duration_seconds' => $totalSeconds,
            'video_file' => $videoFilename,
            'access_type' => $accessType,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.videos.index')
            ->with('success', "Episode #{$episode->episode_number} '{$episode->title}' berhasil ditambahkan.");
    }

    /**
     * Show edit episode form
     */
    public function edit(int $id): View
    {
        $episode = Episode::with('series')->findOrFail($id);
        $seriesList = Series::orderBy('title')->get();

        $minutes = floor($episode->duration_seconds / 60);
        $seconds = $episode->duration_seconds % 60;

        $durationPresets = [
            ['label' => '1m 55s', 'minutes' => 1, 'seconds' => 55],
            ['label' => '1m 58s', 'minutes' => 1, 'seconds' => 58],
            ['label' => '2m 00s', 'minutes' => 2, 'seconds' => 0],
            ['label' => '2m 05s', 'minutes' => 2, 'seconds' => 5],
            ['label' => '2m 14s', 'minutes' => 2, 'seconds' => 14],
            ['label' => '2m 20s', 'minutes' => 2, 'seconds' => 20],
        ];

        return view('admin.videos.edit', compact('episode', 'seriesList', 'minutes', 'seconds', 'durationPresets'));
    }

    /**
     * Update episode
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $episode = Episode::findOrFail($id);

        $validated = $request->validate([
            'series_id' => ['required', 'exists:series,id'],
            'episode_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('episodes')
                    ->where(fn ($query) => $query->where('series_id', $request->series_id))
                    ->ignore($episode->id),
            ],
            'title' => ['required', 'string', 'max:200'],
            'synopsis' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:0', 'max:60'],
            'duration_seconds' => ['nullable', 'integer', 'min:0', 'max:59'],
            'status' => ['required', 'in:draft,published'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:104857600'],
        ]);

        $seconds = (int) ($validated['duration_seconds'] ?? 0);
        $totalSeconds = ($validated['duration_minutes'] * 60) + $seconds;
        $formattedDuration = sprintf('%dm %02ds', $validated['duration_minutes'], $seconds);

        // HUMORA Policy: Ep 1 & 2 are ALWAYS FREE preview; Ep 3+ are PREMIUM
        $accessType = $validated['episode_number'] <= 2 ? 'free' : 'premium';

        $videoFilename = $episode->video_file;
        if ($request->hasFile('video_file')) {
            $newVid = $this->fileStorage->storeUploadedVideoAsWebm(
                $request->file('video_file'),
                'videos',
                'vid_ep' . $validated['episode_number']
            );

            if ($episode->video_file && $episode->video_file !== 'sample-skit.mp4') {
                $this->fileStorage->deleteFile('videos', $episode->video_file);
            }

            $videoFilename = $newVid;
        }

        $episode->update([
            'series_id' => $validated['series_id'],
            'episode_number' => $validated['episode_number'],
            'title' => $validated['title'],
            'synopsis' => $validated['synopsis'] ?? null,
            'duration' => $formattedDuration,
            'duration_seconds' => $totalSeconds,
            'video_file' => $videoFilename,
            'access_type' => $accessType,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.videos.index')
            ->with('success', "Episode #{$episode->episode_number} '{$episode->title}' berhasil diperbarui.");
    }

    /**
     * Delete episode
     */
    public function destroy(int $id): RedirectResponse
    {
        $episode = Episode::findOrFail($id);

        if ($episode->video_file && $episode->video_file !== 'sample-skit.mp4') {
            $this->fileStorage->deleteFile('videos', $episode->video_file);
        }

        $episode->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', "Episode #{$episode->episode_number} berhasil dihapus.");
    }
}
