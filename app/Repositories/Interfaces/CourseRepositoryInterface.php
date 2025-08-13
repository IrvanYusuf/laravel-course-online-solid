<?php

namespace App\Repositories\Interfaces;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    /**
     * Mendapatkan semua kursus.
     *
     * @return Collection<int, Course>
     */
    public function getAll(): Collection;

    /**
     * Mencari kursus berdasarkan ID.
     *
     * @param string $id
     * @return Course|null
     */
    public function find(string $id): ?Course;

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
    public function getByInstructorId(string $instructorId): Collection;

    /**
     * Membuat kursus baru.
     *
     * @param array $data
     * @return Course
     */
    public function create(array $data): Course;

    /**
     * Memperbarui kursus yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return Course
     */
    public function update(string $id, array $data): Course;

    /**
     * Menghapus kursus.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
