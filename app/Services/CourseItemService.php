<?php

namespace App\Services;

use App\Exceptions\CourseItemAlreadyExistsException;
use App\Exceptions\CourseItemNotFoundException;
use App\Models\CourseItem;
use App\Repositories\Interfaces\CourseItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseItemService
{
    /**
     * @var CourseItemRepositoryInterface
     */
    protected $courseItemRepository;
    public function __construct(CourseItemRepositoryInterface $courseItemRepository)
    {
        $this->courseItemRepository = $courseItemRepository;
    }

    /**
     * Mendapatkan semua kursus item berdasarkan kursus id.
     *
     * @param string $courseId
     * @return Collection<int, CourseItem>
     */

    public function getAllCourseItems(string $courseId): Collection
    {
        return $this->courseItemRepository->getByCourse($courseId);
    }

    /**
     * Mencari materi berdasarkan ID kursus item.
     *
     * @param string $courseItemId
     * @return CourseItem|null
     */

    public function getCourseById(string $courseItemId): ?CourseItem
    {
        $courseItem = $this->courseItemRepository->find($courseItemId);
        if (!$courseItem) {
            throw new CourseItemNotFoundException();
        }
        return $courseItem;
    }

    /**
     * Membuat materi baru.
     *
     * @param array $data
     * @return CourseItem
     */
    public function createCourseItem(array $data): CourseItem
    {
        $course = $this->courseItemRepository->findByTitle($data['title'], $data['course_id']);
        if ($course) {
            throw new CourseItemAlreadyExistsException();
        }
        return $this->courseItemRepository->create($data);
    }

    /**
     * Memperbarui materi yang sudah ada.
     *
     * @param string $courseItemId
     * @param array $data
     * @return CourseItem
     */
    public function update(string $courseItemId, array $data): CourseItem
    {
        $this->getCourseById($courseItemId);
        return $this->courseItemRepository->update($courseItemId, $data);
    }

    /**
     * Menghapus materi.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $this->getCourseById($id);
        return $this->courseItemRepository->delete($id);
    }
}
