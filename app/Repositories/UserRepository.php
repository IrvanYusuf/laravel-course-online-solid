<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * 
     * Mendapatkan semua user
     *  
     * @return Collection<int, User>
     * 
     */
    public function getAll(): Collection
    {
        return User::all();
    }


    /**
     * 
     * Menambah data user
     *  
     * @param array $data
     * @return User
     * 
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Mencari user berdasarkan ID.
     *
     * @param string $userId
     * @return User|null
     */
    public function findById(string $userId): ?User
    {
        return User::find($userId);
    }

    /**
     * Mencari user berdasarkan email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Memperbarui user yang sudah ada.
     *
     * @param string $userId
     * @param array $data
     * @return User
     */
    public function update(string $userId, array $data): User
    {
        $user = $this->findById($userId);
        $user->update($data);
        return $user;
    }

    /**
     * Menghapus user.
     *
     * @param string $userId
     * @return bool
     */
    public function delete(string $userId): bool
    {
        $user = $this->findById($userId);
        return $user->delete();
    }
}
