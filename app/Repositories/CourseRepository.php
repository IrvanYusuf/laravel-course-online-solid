<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    /**
     * Mendapatkan semua kursus.
     *
     * @return Collection<int, Course>
     */
    public function getAll(): Collection
    {
        return Course::all();
    }

    /**
     * Mencari kursus berdasarkan ID.
     *
     * @param string $id
     * @return Course|null
     */
    public function find(string $id): ?Course
    {
        return Course::find($id);
    }

    /**
     * Mencari kursus berdasarkan title.
     *
     * @param string $title
     * @return Course|null
     */
    public function findByTitle(string $title): Course|null
    {
        return Course::where("title", $title)->first();
    }

    /**
     * Membuat kursus baru.
     *
     * @param array $data
     * @return Course
     */

    /**
     * Mencari kursus berdasarkan ID instruktur.
     *
     * @param string $instructorId
     * @return Collection<string, Course>
     */
    public function getByInstructorId(string $instructorId): Collection
    {
        $courses = Course::where("instructor_id", $instructorId)->get();
        return $courses;
    }

    /**
     * Membuat kursus baru.
     *
     * @param array $data
     * @return Course
     */
    public function create(array $data): Course
    {
        return Course::create($data);
    }

    /**
     * Memperbarui kursus yang sudah ada berdasarkan id.
     *
     * @param string $id
     * @param array $data
     * @return Course
     */
    public function update(string $id, array $data): Course
    {
        $course = $this->find($id);
        $course->update($data);
        return $course;
    }


    /**
     * Menghapus kursus berdasarkan id.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $course = $this->find($id);
        return $course->delete();
    }
}
