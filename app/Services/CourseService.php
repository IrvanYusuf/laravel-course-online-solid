<?php

namespace App\Services;

use App\Exceptions\CourseAlreadyExistsException;
use App\Exceptions\CourseNotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CourseService
{
    /**
     * @var CourseRepositoryInterface
     */
    protected $courseRepository;
    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Mendapatkan semua kursus.
     *
     * @return Collection<int, Course>
     */
    public function getAllCourses(): Collection
    {
        $start = microtime(true);

        $isFromCache = Cache::has('all_courses');

        // cache selama 10 menit
        $data = Cache::remember("all_courses", 600, function () {
            Log::info("fetching all courses from database");
            return $this->courseRepository->getAll();
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
     * Mendapatkan kursus berdasarkan ID.
     *
     * @param string $id
     * @return Course
     */
    public function getCourseById(string $id): Course
    {
        $course = $this->courseRepository->find($id);
        if (!$course) {
            throw new CourseNotFoundException();
        }
        return $course;
    }

    /**
     * Mencari kursus berdasarkan ID instruktur.
     *
     * @param User $user
     * @return Collection<string, Course>
     */
    public function getByInstructorId(User $user): Collection
    {
        $instructor = $user;
        if ($instructor->role === "STUDENT") {
            throw new UnauthorizedException();
        }
        return $this->courseRepository->getByInstructorId($instructor->id);
    }

    /**
     * Membuat kursus baru.
     *
     * @param array $data
     * @param User $user
     * @return Course
     */
    public function createCourse(array $data, User $user): Course
    {
        // Contoh: Logika bisnis di sini, misalnya validasi role user
        $instructor = $user->role;
        if ($instructor === "STUDENT") {
            throw new UnauthorizedException();
        }
        $course = $this->courseRepository->findByTitle($data['title']);
        if ($course) {
            throw new CourseAlreadyExistsException();
        }
        Log::info("revalidate all courses cache");
        Cache::forget('all_courses');
        return $this->courseRepository->create($data);
    }

    /**
     * Memperbarui kursus.
     *
     * @param string $id
     * @param array $data
     * @return Course
     */
    public function updateCourse(string $id, array $data): Course
    {
        $this->getCourseById($id);
        Log::info("revalidate all courses cache");
        Cache::forget('all_courses');
        return $this->courseRepository->update($id, $data);
    }

    /**
     * Menghapus kursus.
     *
     * @param string $id
     * @return bool
     */
    public function deleteCourse(string $id): bool
    {
        $this->getCourseById($id);
        Log::info("revalidate all courses cache");
        Cache::forget('all_courses');
        return $this->courseRepository->delete($id);
    }
}
