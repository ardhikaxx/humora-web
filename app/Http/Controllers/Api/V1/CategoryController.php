<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
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
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data' => CategoryResource::collection($categories),
        ]);
    }
}
