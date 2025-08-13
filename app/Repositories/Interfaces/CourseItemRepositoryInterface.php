<?php

namespace App\Repositories\Interfaces;

use App\Models\CourseItem;
use Illuminate\Database\Eloquent\Collection;

interface CourseItemRepositoryInterface
{
    /**
     * Mendapatkan semua materi untuk kursus tertentu.
     *
     * @param string $courseId
     * @return Collection<string, CourseItem>
     */
    public function getByCourse(string $courseId): Collection;

    /**
     * Mencari materi berdasarkan ID kursus item.
     *
     * @param string $id
     * @return CourseItem|null
     */
    public function find(string $id): ?CourseItem;

    /**
     * Membuat materi baru.
     *
     * @param array $data
     * @return CourseItem
     */
    public function create(array $data): CourseItem;

    /**
     * Memperbarui materi yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return CourseItem
     */
    public function update(string $id, array $data): CourseItem;

    /**
     * Menghapus materi.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
