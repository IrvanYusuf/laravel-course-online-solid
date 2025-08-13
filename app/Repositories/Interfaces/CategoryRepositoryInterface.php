<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Mendapatkan semua kategori.
     *
     * @return Collection<int, Category>
     */
    public function getAll(): Collection;

    /**
     * Mencari kategory berdasarkan ID.
     *
     * @param string $id
     * @return Category
     */
    public function find(string $id): Category;

    /**
     * Mencari kategory berdasarkan name.
     *
     * @param string $name
     * @return Category
     */
    public function findByName(string $name): Category;

    /**
     * Membuat kategori baru.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * Memperbarui kategori yang sudah ada.
     *
     * @param string $id
     * @param array $data
     * @return Category
     */
    public function update(string $id, array $data): Category;

    /**
     * Menghapus kategori.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}
