<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Series;
use App\Services\FileStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $seriesList = Series::with('categories')->orderBy('title')->get();
        $selectedSeriesId = (int) $request->input('series_id', $seriesList->first()?->id);

        // Pre-calculate next episode numbers and existing counts for every series
        $epStats = Episode::select('series_id', DB::raw('MAX(episode_number) as max_ep'), DB::raw('COUNT(*) as total_ep'))
            ->groupBy('series_id')
            ->get()
            ->keyBy('series_id');

        $nextEpisodeNumbers = [];
        $seriesEpisodeCounts = [];
        foreach ($seriesList as $s) {
            $stat = $epStats->get($s->id);
            $nextEpisodeNumbers[$s->id] = $stat ? ($stat->max_ep + 1) : 1;
            $seriesEpisodeCounts[$s->id] = $stat ? (int) $stat->total_ep : 0;
        }

        $suggestedNumber = $nextEpisodeNumbers[$selectedSeriesId] ?? 1;

        $durationPresets = [
            ['label' => '1m 55s', 'minutes' => 1, 'seconds' => 55],
            ['label' => '1m 58s', 'minutes' => 1, 'seconds' => 58],
            ['label' => '2m 00s', 'minutes' => 2, 'seconds' => 0],
            ['label' => '2m 05s', 'minutes' => 2, 'seconds' => 5],
            ['label' => '2m 14s', 'minutes' => 2, 'seconds' => 14],
            ['label' => '2m 20s', 'minutes' => 2, 'seconds' => 20],
        ];

        return view('admin.videos.create', compact(
            'seriesList',
            'selectedSeriesId',
            'suggestedNumber',
            'nextEpisodeNumbers',
            'seriesEpisodeCounts',
            'durationPresets'
        ));
    }

    /**
     * Store new episode
     */
    public function store(Request $request): RedirectResponse
    {
        $seriesId = (int) $request->input('series_id');
        $autoEpisodeNumber = (Episode::where('series_id', $seriesId)->max('episode_number') ?? 0) + 1;

        // Automatically assign episode number if omitted or empty
        if (!$request->filled('episode_number')) {
            $request->merge(['episode_number' => $autoEpisodeNumber]);
        }

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
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:60'],
            'duration_seconds' => ['nullable', 'integer', 'min:0', 'max:59'],
            'access_type' => ['nullable', 'in:free,premium'],
            'status' => ['required', 'in:draft,published'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:104857600'], // Max 100MB
        ]);

        // Auto-detect duration from uploaded video master file if available
        $detectedDuration = null;
        if ($request->hasFile('video_file')) {
            $tempPath = $request->file('video_file')->getRealPath();
            $detectedDuration = FileStorageService::getVideoDurationSeconds($tempPath);
        }

        if ($detectedDuration !== null && $detectedDuration > 0) {
            $totalSeconds = $detectedDuration;
            $minutes = (int) floor($totalSeconds / 60);
            $seconds = (int) ($totalSeconds % 60);
            $formattedDuration = sprintf('%dm %02ds', $minutes, $seconds);
        } else {
            $minutes = isset($validated['duration_minutes']) && $validated['duration_minutes'] !== null
                ? (int) $validated['duration_minutes']
                : 2;
            $seconds = isset($validated['duration_seconds']) && $validated['duration_seconds'] !== null
                ? (int) $validated['duration_seconds']
                : 0;
            $totalSeconds = ($minutes * 60) + $seconds;
            if ($totalSeconds <= 0) {
                $totalSeconds = 120; // 2 minutes standard
                $minutes = 2;
                $seconds = 0;
            }
            $formattedDuration = sprintf('%dm %02ds', $minutes, $seconds);
        }

        // Access Type: Admin can explicitly choose 'free' or 'premium', or fallback to trial policy
        if (!empty($validated['access_type'])) {
            $accessType = $validated['access_type'];
        } else {
            $accessType = $validated['episode_number'] <= 2 ? 'free' : 'premium';
        }

        $videoFilename = null;
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
        $seriesList = Series::with('categories')->orderBy('title')->get();

        // Pre-calculate next episode numbers and existing counts for every series
        $epStats = Episode::select('series_id', DB::raw('MAX(episode_number) as max_ep'), DB::raw('COUNT(*) as total_ep'))
            ->groupBy('series_id')
            ->get()
            ->keyBy('series_id');

        $nextEpisodeNumbers = [];
        $seriesEpisodeCounts = [];
        foreach ($seriesList as $s) {
            $stat = $epStats->get($s->id);
            // If it's the current series of this episode, preserve its current episode number
            $nextEpisodeNumbers[$s->id] = ($s->id === $episode->series_id)
                ? $episode->episode_number
                : ($stat ? ($stat->max_ep + 1) : 1);
            $seriesEpisodeCounts[$s->id] = $stat ? (int) $stat->total_ep : 0;
        }

        $minutes = (int) floor($episode->duration_seconds / 60);
        $seconds = (int) ($episode->duration_seconds % 60);

        $durationPresets = [
            ['label' => '1m 55s', 'minutes' => 1, 'seconds' => 55],
            ['label' => '1m 58s', 'minutes' => 1, 'seconds' => 58],
            ['label' => '2m 00s', 'minutes' => 2, 'seconds' => 0],
            ['label' => '2m 05s', 'minutes' => 2, 'seconds' => 5],
            ['label' => '2m 14s', 'minutes' => 2, 'seconds' => 14],
            ['label' => '2m 20s', 'minutes' => 2, 'seconds' => 20],
        ];

        return view('admin.videos.edit', compact(
            'episode',
            'seriesList',
            'minutes',
            'seconds',
            'durationPresets',
            'nextEpisodeNumbers',
            'seriesEpisodeCounts'
        ));
    }

    /**
     * Update episode
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $episode = Episode::findOrFail($id);

        $seriesId = (int) $request->input('series_id', $episode->series_id);
        $autoEpisodeNumber = ($seriesId === $episode->series_id)
            ? $episode->episode_number
            : ((Episode::where('series_id', $seriesId)->max('episode_number') ?? 0) + 1);

        if (!$request->filled('episode_number')) {
            $request->merge(['episode_number' => $autoEpisodeNumber]);
        }

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
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:60'],
            'duration_seconds' => ['nullable', 'integer', 'min:0', 'max:59'],
            'access_type' => ['required', 'in:free,premium'],
            'status' => ['required', 'in:draft,published'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:104857600'],
        ]);

        // Auto-detect duration from uploaded video master file if available
        $detectedDuration = null;
        if ($request->hasFile('video_file')) {
            $tempPath = $request->file('video_file')->getRealPath();
            $detectedDuration = FileStorageService::getVideoDurationSeconds($tempPath);
        }

        if ($detectedDuration !== null && $detectedDuration > 0) {
            $totalSeconds = $detectedDuration;
            $minutes = (int) floor($totalSeconds / 60);
            $seconds = (int) ($totalSeconds % 60);
            $formattedDuration = sprintf('%dm %02ds', $minutes, $seconds);
        } else {
            $minutes = isset($validated['duration_minutes']) && $validated['duration_minutes'] !== null
                ? (int) $validated['duration_minutes']
                : (int) floor($episode->duration_seconds / 60);
            $seconds = isset($validated['duration_seconds']) && $validated['duration_seconds'] !== null
                ? (int) $validated['duration_seconds']
                : (int) ($episode->duration_seconds % 60);
            $totalSeconds = ($minutes * 60) + $seconds;
            if ($totalSeconds <= 0) {
                $totalSeconds = $episode->duration_seconds > 0 ? $episode->duration_seconds : 120;
                $minutes = (int) floor($totalSeconds / 60);
                $seconds = (int) ($totalSeconds % 60);
            }
            $formattedDuration = sprintf('%dm %02ds', $minutes, $seconds);
        }

        // Access Type: Admin can explicitly choose 'free' or 'premium', or fallback to trial policy
        if (!empty($validated['access_type'])) {
            $accessType = $validated['access_type'];
        } else {
            $accessType = $validated['episode_number'] <= 2 ? 'free' : 'premium';
        }

        $videoFilename = $episode->video_file;
        if ($request->hasFile('video_file')) {
            $newVid = $this->fileStorage->storeUploadedVideoAsWebm(
                $request->file('video_file'),
                'videos',
                'vid_ep' . $validated['episode_number']
            );

            if ($episode->video_file) {
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

        if ($episode->video_file) {
            $this->fileStorage->deleteFile('videos', $episode->video_file);
        }

        $episode->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', "Episode #{$episode->episode_number} berhasil dihapus.");
    }
}
