<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Menampilkan daftar semua kategori.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return response()->json([
            "message" => "success get all categories",
            "data" => $categories
        ]);
    }

    /**
     * Menyimpan kategori baru.
     *
     * @param CreateCategoryRequest $request
     * @return JsonResponse
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $category = $this->categoryService->createNewCategory($validatedData);
        return response()->json([
            "message" => "success create a new category",
            "data" => $category
        ], 201);
    }

    /**
     * Menampilkan satu kategori berdasarkan ID.
     *
     * @param string $categoryId
     * @return JsonResponse
     */
    public function show(string $categoryId): JsonResponse
    {
        $category = $this->categoryService->getCategoryById($categoryId);
        return response()->json([
            "message" => "success get category",
            "data" => $category
        ]);
    }

    /**
     * Memperbarui kategori yang sudah ada.
     *
     * @param UpdateCategoryRequest $request
     * @param string $categoryId
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, string $categoryId): JsonResponse
    {
        $validated = $request->validated();
        $category = $this->categoryService->update($categoryId, $validated);
        return response()->json([
            "message" => "success update category",
            "data" => $category
        ]);
    }

    /**
     * Menghapus kategori dari database.
     *
     * @param string $categoryId
     * @return JsonResponse
     */
    public function destroy(string $categoryId): JsonResponse
    {
        $this->categoryService->delete($categoryId);
        return response()->json([
            "message" => "success delete category",
        ]);
    }
}
