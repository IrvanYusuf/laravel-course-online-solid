<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentItemRepository implements EnrollmentRepositoryInterface
{
    /**
     * Mendapatkan semua pendaftaran.
     *
     * @return Collection<int, Enrollment>
     */
    public function getAll(): Collection
    {
        return Enrollment::all();
    }

    /**
     * Mencari pendaftaran berdasarkan student dan kursus.
     *
     * @param string $studentId
     * @param string $courseId
     * @return Enrollment|null
     */
    public function findByStudentAndCourse(string $studentId, string $courseId): ?Enrollment
    {
        $enrollment = Enrollment::where("student_id", $studentId)
            ->where("course_id", $courseId)->first();
        return $enrollment;
    }

    /**
     * Membuat pendaftaran baru.
     *
     * @param array $data
     * @return Enrollment
     */
    public function create(array $data): Enrollment
    {
        return Enrollment::create($data);
    }

    /**
     * Memperbarui pendaftaran yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return Enrollment
     */
    public function update(string $id, array $data): Enrollment
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update($data);
        return $enrollment;
    }

    /**
     * Menghapus pendaftaran.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $enrollment = Enrollment::findOrFail($id);
        return $enrollment->delete();
    }
}
