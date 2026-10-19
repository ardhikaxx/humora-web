<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Series;
use App\Services\FileStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SeriesController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorage
    ) {}

    /**
     * Display series list with search, category filtering, and episode counts
     */
    public function index(Request $request): View
    {
        $query = Series::with(['categories'])
            ->withCount(['episodes', 'publishedEpisodes', 'likes'])
            ->orderBy('catalog_order')
            ->orderByDesc('id');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%");
            });
        }

        if ($catId = $request->input('category_id')) {
            $query->whereHas('categories', function ($q) use ($catId) {
                $q->where('categories.id', $catId);
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $seriesList = $query->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.series.index', compact('seriesList', 'categories'));
    }

    /**
     * Show create series form
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.series.create', compact('categories'));
    }

    /**
     * Store new series
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', 'unique:series,slug'],
            'synopsis' => ['required', 'string'],
            'release_year' => ['required', 'string', 'max:10'],
            'catalog_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Max 5MB
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $originalSlug = $slug;
        $counter = 1;
        while (Series::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Store, compress, and convert thumbnail directly to WebP (no symlink!)
        $thumbnailFile = $this->fileStorage->storeUploadedImageAsWebp(
            $request->file('thumbnail'),
            'thumbnails',
            'thumb_' . $slug,
            82,
            10485760
        );

        $series = DB::transaction(function () use ($validated, $slug, $thumbnailFile, $request) {
            $s = Series::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'synopsis' => $validated['synopsis'],
                'release_year' => $validated['release_year'],
                'catalog_order' => $validated['catalog_order'] ?? 0,
                'is_featured' => $request->boolean('is_featured', false),
                'status' => $validated['status'],
                'thumbnail' => $thumbnailFile,
            ]);

            $s->categories()->sync($validated['categories']);
            return $s;
        });

        return redirect()->route('admin.series.index')
            ->with('success', "Series '{$series->title}' berhasil dibuat.");
    }

    /**
     * Show single series with its episodes
     */
    public function show(int $id): View
    {
        $series = Series::with(['categories', 'episodes' => function ($q) {
            $q->withCount('likes');
        }])->withCount('likes')->findOrFail($id);
        return view('admin.series.show', compact('series'));
    }

    /**
     * Show edit series form
     */
    public function edit(int $id): View
    {
        $series = Series::with('categories')->findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.series.edit', compact('series', 'categories'));
    }

    /**
     * Update series
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $series = Series::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', 'unique:series,slug,' . $series->id],
            'synopsis' => ['required', 'string'],
            'release_year' => ['required', 'string', 'max:10'],
            'catalog_order' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $thumbnailFile = $series->thumbnail;

        if ($request->hasFile('thumbnail')) {
            // Upload, compress, and convert new thumbnail to WebP
            $newThumb = $this->fileStorage->storeUploadedImageAsWebp(
                $request->file('thumbnail'),
                'thumbnails',
                'thumb_' . $slug,
                82,
                10485760
            );

            // Clean up old thumbnail if it exists
            if ($series->thumbnail && $series->thumbnail !== 'cara-deketin-cewek.jpg' && $series->thumbnail !== 'drama-warga-grup-rt.jpg') {
                $this->fileStorage->deleteFile('thumbnails', $series->thumbnail);
            }

            $thumbnailFile = $newThumb;
        }

        DB::transaction(function () use ($series, $validated, $slug, $thumbnailFile, $request) {
            $series->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'synopsis' => $validated['synopsis'],
                'release_year' => $validated['release_year'],
                'catalog_order' => $validated['catalog_order'] ?? $series->catalog_order,
                'is_featured' => $request->boolean('is_featured', false),
                'status' => $validated['status'],
                'thumbnail' => $thumbnailFile,
            ]);

            $series->categories()->sync($validated['categories']);
        });

        return redirect()->route('admin.series.index')
            ->with('success', "Series '{$series->title}' berhasil diperbarui.");
    }

    /**
     * Delete series and its associated assets
     */
    public function destroy(int $id): RedirectResponse
    {
        $series = Series::with('episodes')->findOrFail($id);

        DB::transaction(function () use ($series) {
            // Delete thumbnail file
            if ($series->thumbnail) {
                $this->fileStorage->deleteFile('thumbnails', $series->thumbnail);
            }

            // Delete series (cascade deletes episodes and histories)
            $series->delete();
        });

        return redirect()->route('admin.series.index')
            ->with('success', "Series '{$series->title}' beserta seluruh episode terkait berhasil dihapus.");
    }
}
