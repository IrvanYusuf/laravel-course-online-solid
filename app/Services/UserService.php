<?php

namespace App\Services;

use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * 
     * Mendapatkan semua user
     *  
     * @param User $user
     * @return Collection<int, User>
     * 
     */
    public function getAllUsers(User $user): Collection
    {
        if ($user->role !== "ADMIN") {
            throw new UnauthorizedException();
        }
        return $this->userRepository->getAll();
    }


    /**
     * 
     * Menambah data user
     *  
     * @param array $data
     * @return User
     * 
     */
    public function createUser(array $data): User
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if ($user) {
            throw new UserAlreadyExistsException();
        }
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }



    /**
     * Mencari user berdasarkan id.
     *
     * @param string $userId
     * @return User
     */
    public function getUserById(string $userId): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }


    /**
     * Memperbarui user yang sudah ada.
     *
     * @param string $userId
     * @param array $data
     * @return User
     */
    public function updateUser(string $userId, array $data): User
    {
        $this->getUserById($userId);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->update($userId, $data);
    }

    /**
     * Menghapus user.
     *
     * @param string $userId
     * @return bool
     */
    public function deleteUser(string $userId): bool
    {
        $this->getUserById($userId);
        return $this->userRepository->delete($userId);
    }
}
