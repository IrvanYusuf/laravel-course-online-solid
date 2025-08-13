<?php

namespace App\Repositories\Interfaces;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    /**
     * Mendapatkan semua pendaftaran.
     *
     * @return Collection<int, Enrollment>
     */
    public function getAll(): Collection;

    /**
     * Mencari pendaftaran berdasarkan student dan kursus.
     *
     * @param string $studentId
     * @param string $courseId
     * @return Enrollment|null
     */
    public function findByStudentAndCourse(string $studentId, string $courseId): ?Enrollment;


    /**
     * Mencari pendaftaran berdasarkan id.
     *
     * @param string $enrollId
     * @return Enrollment|null
     */
    public function find(string $enrollId): ?Enrollment;

    /**
     * Mencari kursus yang sudah dibeli user (payment status = PAID) 
     * 
     * @param string $studentId
     * @return Collection<int,Enrollment>
     */
    public function getPurchasedByUser(string $studentId): Collection;

    /**
     * Membuat pendaftaran baru.
     *
     * @param array $data
     * @return Enrollment
     */
    public function create(array $data): Enrollment;

    /**
     * Memperbarui pendaftaran yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return Enrollment
     */


    public function update(string $id, array $data): Enrollment;

    /**
     * Menghapus pendaftaran.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
