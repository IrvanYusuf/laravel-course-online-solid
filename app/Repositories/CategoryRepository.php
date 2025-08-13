<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * 
     * Mendapatkan semua data kategori
     * 
     * @return Collection<int, Category>
     * 
     */
    public function getAll(): Collection
    {
        return Category::all();
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
    public function find(string $id): Category
    {
        return Category::find($id);
    }

    /**
     * 
     * Mendapatkan data kategori berdasarkan name
     * 
     * @param string $name
     * 
     * @return Category
     * 
     */
    public function findByName(string $name): Category
    {
        return Category::where("name", $name)->first();
    }

    /**
     * Membuat kategori baru.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
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
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }

    /**
     * Menghapus kategori.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $category = $this->find($id);
        return $category->delete();
    }
}
