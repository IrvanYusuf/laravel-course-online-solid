<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * @var EnrollmentService
     */
    protected $enrollmentService;

    /**
     * EnrollmentController constructor.
     *
     * @param EnrollmentService $enrollmentService
     */
    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Menampilkan daftar semua pendaftaran.
     * Endpoint: GET /api/enrollments
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $enrollments = $this->enrollmentService->getAllEnrollments($user);

        return response()->json([
            "message" => "Successfully retrieved all enrollments.",
            "data" => $enrollments
        ]);
    }

    /**
     * Membuat pendaftaran baru.
     *
     * @param CreateEnrollmentRequest $request
     * @return JsonResponse
     */
    public function store(CreateEnrollmentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $enrollment = $this->enrollmentService->enrollStudentInCourse($validated);

        return response()->json([
            "message" => "Enrollment created successfully.",
            "data" => $enrollment
        ], 201);
    }

    /**
     * Menampilkan detail pendaftaran.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $enrollment = $this->enrollmentService->getEnrollmentById($id);

        return response()->json([
            "message" => "Enrollment retrieved successfully.",
            "data" => $enrollment
        ]);
    }


    /**
     * Menampilkan kursus yang pernah dibeli student.
     *
     * @return JsonResponse
     */
    public function getMyCourses(): JsonResponse
    {
        $studentId = Auth::user()->id;
        $myCourses = $this->enrollmentService->getMyCourses($studentId);
        return response()->json([
            "message" => "success get my courses.",
            "data" => $myCourses
        ]);
    }


    /**
     * Memperbarui pendaftaran.
     *
     * @param UpdateEnrollmentRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateEnrollmentRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $enrollment = $this->enrollmentService->updateEnrollment($id, $validated);

        return response()->json([
            "message" => "Enrollment updated successfully.",
            "data" => $enrollment
        ]);
    }

    /**
     * Menghapus pendaftaran.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $user = Auth::user();
        $this->enrollmentService->deleteEnrollment($id, $user);

        return response()->json([
            "message" => "success delete enrollment"
        ]);
    }
}
