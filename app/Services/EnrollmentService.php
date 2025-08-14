<?php

namespace App\Services;

use App\Exceptions\EnrollCourseAlreadyExistsException;
use App\Exceptions\EnrollNotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EnrollmentService
{
    /**
     * @var EnrollmentRepositoryInterface
     */
    protected $enrollmentRepository;
    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Mendapatkan semua pendaftaran yang ada.
     *
     * @param User $user
     * @return Collection<int, Enrollment> Mengembalikan koleksi semua objek pendaftaran.
     */
    public function getAllEnrollments(User $user): Collection
    {
        if ($user->role !== "ADMIN") {
            throw new UnauthorizedException();
        }
        return $this->enrollmentRepository->getAll();
    }

    /**
     * Membuat pendaftaran baru/membeli course.
     *
     * @param array $data
     * @throws EnrollCourseAlreadyExistsException
     * @return Enrollment
     */
    public function enrollStudentInCourse(array $data): Enrollment
    {
        $enrollment = $this->enrollmentRepository->findByStudentAndCourse($data['student_id'], $data['course_id']);

        if ($enrollment) {
            throw new EnrollCourseAlreadyExistsException();
        }
        Cache::forget("user_{$data['student_id']}_my_courses");
        return $this->enrollmentRepository->create($data);
    }

    /**
     * Mencari kursus yang sudah dibeli user (payment status = PAID) 
     * 
     * @param string $studentId
     * @return Collection<int,Enrollment>
     */
    public function getMyCourses(string $studentId): Collection
    {
        $start = microtime(true);

        $isFromCache = Cache::has("user_{$studentId}_my_courses");
        // cache selamat 5 menit
        $data = Cache::remember("user_{$studentId}_my_courses", 300, function () use ($studentId) {
            Log::info('Fetching all my courses from database.');
            return $this->enrollmentRepository->getPurchasedByUser($studentId);
        });
        $end = microtime(true); // Selesai timer
        $executionTime = ($end - $start) * 1000; // ms

        Log::info('Execution Time', [
            'source' => $isFromCache ? 'CACHE' : 'DATABASE',
            'time_ms' => $executionTime
        ]);

        return $data;
    }


    /**
     * Mendapatkan pendaftaran berdasarkan ID.
     *
     * @param string $enrollmentId ID dari pendaftaran.
     * @return Enrollment
     * @throws EnrollNotFoundException
     */
    public function getEnrollmentById(string $enrollmentId): Enrollment
    {
        $enrollment = $this->enrollmentRepository->find($enrollmentId);
        if (!$enrollment) {
            throw new EnrollNotFoundException();
        }
        return $enrollment;
    }

    /**
     * Memperbarui status pendaftaran atau status pembayaran yang sudah ada.
     *
     * @param string $enrollmentId ID dari pendaftaran yang akan diperbarui.
     * @param array $data Data yang valid untuk memperbarui pendaftaran.
     * @return Enrollment Mengembalikan objek pendaftaran yang telah diperbarui.
     * @throws EnrollNotFoundException Jika pendaftaran tidak ditemukan.
     */
    public function updateEnrollment(string $enrollmentId, array $data): Enrollment
    {
        // Panggil metode getEnrollmentById untuk memeriksa keberadaan pendaftaran
        $enrollment = $this->getEnrollmentById($enrollmentId);
        Cache::forget("user_{$enrollment->student_id}_my_courses");
        return $this->enrollmentRepository->update($enrollmentId, $data);
    }

    /**
     * Menghapus pendaftaran dari database.
     *
     * @param string $enrollmentId ID dari pendaftaran yang akan dihapus.
     * @return bool Mengembalikan true jika penghapusan berhasil.
     * @throws EnrollNotFoundException Jika pendaftaran tidak ditemukan.
     * @throws UnauthorizedException Jika role bukan admin.
     */
    public function deleteEnrollment(string $enrollmentId, User $user): bool
    {
        if ($user->role !== "ADMIN") {
            throw new UnauthorizedException();
        }
        // Panggil metode getEnrollmentById untuk memeriksa keberadaan pendaftaran
        $enrollment = $this->getEnrollmentById($enrollmentId);
        Cache::forget("user_{$enrollment->student_id}_my_courses");

        return $this->enrollmentRepository->delete($enrollmentId);
    }
}
