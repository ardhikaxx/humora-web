<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get list of active categories
     */
    public function index(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->withCount(['series' => function ($q) {
                $q->where('status', 'published');
            }])
            ->get()
            ->map(fn (Category $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'description' => $c->description,
                'series_count' => $c->series_count,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data' => $categories,
        ]);
    }
}
