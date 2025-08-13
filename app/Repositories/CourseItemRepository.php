<?php

namespace App\Repositories;

use App\Models\CourseItem;
use App\Repositories\Interfaces\CourseItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseItemRepository implements CourseItemRepositoryInterface
{
    /**
     * Mendapatkan semua materi untuk kursus tertentu.
     *
     * @param string $courseId
     * @return Collection<string, CourseItem>
     */
    public function getByCourse(string $courseId): Collection
    {
        $courseItems = CourseItem::where("course_id", $courseId)->get();
        return $courseItems;
    }

    /**
     * Mencari materi berdasarkan ID kursus item.
     *
     * @param string $id
     * @return CourseItem|null
     */
    public function find(string $id): ?CourseItem
    {
        return CourseItem::findOrFail($id);
    }

    /**
     * Membuat materi baru.
     *
     * @param array $data
     * @return CourseItem
     */
    public function create(array $data): CourseItem
    {
        return CourseItem::create($data);
    }

    /**
     * Memperbarui materi yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return CourseItem
     */
    public function update(string $id, array $data): CourseItem
    {
        $courseItem = $this->find($id);
        $courseItem->update($data);
        return $courseItem;
    }


    /**
     * Menghapus materi.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $courseItem = $this->find($id);
        return $courseItem->delete();
    }
}
