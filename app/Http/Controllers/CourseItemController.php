<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCourseItemRequest;
use App\Http\Requests\UpdateCourseItemRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\CourseItemService;
use Illuminate\Http\JsonResponse;

class CourseItemController extends Controller
{
    /**
     * @var CourseItemService
     */
    protected $courseItemService;

    /**
     * CourseItemController constructor.
     *
     * @param CourseItemService $courseItemService
     */
    public function __construct(CourseItemService $courseItemService)
    {
        $this->courseItemService = $courseItemService;
    }

    /**
     * Menampilkan daftar semua kursus item.
     *
     * @param string $courseId
     * @return JsonResponse
     */

    public function index(string $courseId): JsonResponse
    {
        $courseItems = $this->courseItemService->getAllCourseItems($courseId);
        return response()->json([
            "message" => "success get course items",
            "data" => $courseItems
        ]);
    }

    /**
     * Menampilkan materi berdasarkan ID kursus item.
     *
     * @param string $courseItemId
     * @return JsonResponse
     */

    public function show(string $courseItemId): JsonResponse
    {
        $courseItem = $this->courseItemService->getCourseById($courseItemId);
        return response()->json([
            "message" => "success get course item",
            "data" => $courseItem
        ]);
    }

    /**
     * Membuat materi baru.
     *
     * @param CreateCourseItemRequest $request
     * @return JsonResponse
     */

    public function store(CreateCourseItemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $courseItem = $this->courseItemService->createCourseItem($validated);
        return response()->json([
            "message" => "success create a new course item",
            "data" => $courseItem
        ], 201);
    }

    /**
     * Memperbarui materi yang sudah ada.
     *
     * @param string $courseItemId
     * @param UpdateCourseItemRequest $request
     * @return JsonResponse
     */
    public function update(UpdateCourseItemRequest $request, string $courseItemId): JsonResponse
    {
        $validated = $request->validated();
        $courseItem = $this->courseItemService->update($courseItemId, $validated);

        return response()->json([
            "message" => "success update course item",
            "data" => $courseItem
        ]);
    }

    /**
     * Menghapus materi.
     *
     * @param string $courseItemId
     * @return JsonResponse
     */
    public function destroy(string $courseItemId): JsonResponse
    {
        $this->courseItemService->delete($courseItemId);
        return response()->json([
            "message" => "success delete course item",
        ]);
    }
}
