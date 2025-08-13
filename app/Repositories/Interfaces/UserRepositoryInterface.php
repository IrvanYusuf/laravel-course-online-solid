<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Mendapatkan semua user.
     *
     * @return Collection<int, User>
     */
    public function getAll(): Collection;

    /**
     * 
     * Menambah data user
     *  
     * @param array $data
     * @return User
     * 
     */
    public function create(array $data): User;


    /**
     * Mencari user berdasarkan email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;


    /**
     * Mencari user berdasarkan ID.
     *
     * @param string $userId
     * @return User|null
     */
    public function findById(string $userId): ?User;

    /**
     * Memperbarui user yang sudah ada.
     *
     * @param string $userId
     * @param array $data
     * @return User
     */
    public function update(string $userId, array $data): ?User;

    /**
     * Menghapus user.
     *
     * @param string $userId
     * @return bool
     */
    public function delete(string $userId): bool;
}
