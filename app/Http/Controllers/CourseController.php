<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * @var CourseService
     */
    protected $courseService;

    /**
     * CourseController constructor.
     *
     * @param CourseService $courseService
     */
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Menampilkan daftar semua kursus.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $courses = $this->courseService->getAllCourses();
        return response()->json([
            "message" => "success get all courses",
            "data" => $courses
        ]);
    }

    /**
     * Menyimpan kursus baru.
     *
     * @param CreateCourseRequest $request
     * @return JsonResponse
     */
    public function store(CreateCourseRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        $course = $this->courseService->createCourse($validated, $user);
        return response()->json([
            "message" => "success get create new course",
            "data" => $course
        ], 201);
    }

    /**
     * Menampilkan satu kursus berdasarkan ID.
     *
     * @param string $courseId
     * @return JsonResponse
     */
    public function show(string $courseId): JsonResponse
    {
        $course = $this->courseService->getCourseById($courseId);
        return response()->json([
            "message" => "success get course",
            "data" => $course
        ]);
    }

    /**
     * Mencari kursus berdasarkan ID instruktur.
     *
     * @return JsonResponse<string, Course>
     */
    public function getByInstructorId(): JsonResponse
    {
        $user = Auth::user();
        $coursesByInstructorId = $this->courseService->getByInstructorId($user);
        return response()->json([
            "message" => "success get courses instructor",
            "data" => $coursesByInstructorId
        ]);
    }

    /**
     * Memperbarui kursus yang sudah ada.
     *
     * @param UpdateCourseRequest $request
     * @param string $courseId
     * @return JsonResponse
     */
    public function update(UpdateCourseRequest $request, string $courseId): JsonResponse
    {
        $validated = $request->validated();
        $course = $this->courseService->updateCourse($courseId, $validated);
        return response()->json([
            "message" => "success update course",
            "data" => $course
        ]);
    }

    /**
     * Menghapus kursus dari database.
     *
     * @param string $courseId
     * @return JsonResponse
     */
    public function destroy(string $courseId): JsonResponse
    {
        $this->courseService->deleteCourse($courseId);
        return response()->json([
            "message" => "success delete course",
        ]);
    }
}
