<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function getAllUsers()
    {
        $user = auth()->user();
        $users = $this->userService->getAllUsers($user);
        return response()->json([
            "message" => "Success get all users",
            "data" => $users
        ]);
    }

    public function createUser(CreateUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userService->createUser($validatedData);

        return response()->json([
            "message" => "success create new user",
            "data" => $user
        ], 201);
    }

    public function getUserById(string $userId)
    {
        $user = $this->userService->getUserById($userId);

        return response()->json([
            "message" => "success get a user by id",
            "data" => $user
        ]);
    }

    public function updateUser(UpdateUserRequest $updateUser, string $userId)
    {
        $validatedData = $updateUser->validated();

        $user = $this->userService->updateUser($userId, $validatedData);
        return response()->json([
            "message" => "success update user",
            "data" => $user
        ]);
    }

    public function deleteUser(string $userId)
    {
        $this->userService->deleteUser($userId);
        return response()->json([
            "message" => "success delete user",
        ]);
    }
}
