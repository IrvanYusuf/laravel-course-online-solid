<?php

namespace App\Services;

use App\Exceptions\CategoryAlreadyExistsException;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    protected $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Mendapatkan semua kategori dari database.
     *
     * @return Collection<int, Category>
     */
    public function getAllCategories(): Collection
    {
        $start = microtime(true);

        $isFromCache = Cache::has('categories_all');

        // cache selama 10 menit
        $data = Cache::remember('categories_all', 600, function () {
            Log::info('Fetching all categories from database.');
            return $this->categoryRepository->getAll();
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
     * 
     * Mendapatkan data kategori berdasarkan id
     * 
     * @param string $id
     * 
     * @return Category
     * 
     */
    public function getCategoryById(string $id): Category
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new CategoryNotFoundException();
        }
        return $category;
    }

    /**
     * Membuat kategori baru.
     *
     * @param array $data
     * @return Category
     */
    public function createNewCategory(array $data): Category
    {
        $category = $this->categoryRepository->findByName($data['name']);
        if ($category) {
            throw new CategoryAlreadyExistsException();
        }
        Cache::forget('categories_all');
        return Category::create($data);
    }


    /**
     * Memperbarui kategori yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return Category
     */
    public function update(string $id, array $data): Category
    {
        $this->getCategoryById($id);
        Cache::forget('categories_all');
        return $this->categoryRepository->update($id, $data);
    }

    /**
     * Menghapus kategori.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $this->getCategoryById($id);
        Cache::forget('categories_all');
        return $this->categoryRepository->delete($id);
    }
}
