<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    /**
     * Mendapatkan semua pendaftaran.
     *
     * @return Collection<int, Enrollment>
     */
    public function getAll(): Collection
    {
        return Enrollment::with(['student', 'course'])->get();
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
     * Mencari pendaftaran berdasarkan id.
     *
     * @param string $enrollId
     * @return Enrollment|null
     */
    public function find(string $enrollId): ?Enrollment
    {
        return Enrollment::with('course')->find($enrollId);
    }

    /**
     * Mencari kursus yang sudah dibeli user (payment status = PAID) 
     * 
     * @param string $studentId
     * @return Collection<int,Enrollment>
     */
    public function getPurchasedByUser(string $studentId): Collection
    {
        return Enrollment::with('course')
            ->where('student_id', $studentId)
            ->where('payment_status', 'PAID')
            ->get();
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
