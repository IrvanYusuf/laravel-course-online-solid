<?php

namespace App\Services;

use App\Exceptions\CategoryAlreadyExistsException;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
        $categories = $this->categoryRepository->getAll();
        return $categories;
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
        return $this->categoryRepository->delete($id);
    }
}
