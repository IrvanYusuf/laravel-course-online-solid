<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Interfaces\AuthServiceInterface;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $response = $this->authService->register($validatedData);
        return response()->json([
            "message" => "Success register",
            "data" => [
                "user" => $response["user"],
                "token" => $response["token"]
            ]
        ]);
    }

    public function login(LoginRequest $loginRequest)
    {
        $validatedData = $loginRequest->validated();
        $response = $this->authService->login($validatedData);
        return response()->json([
            "message" => "Success login",
            "data" => [
                "user" => $response['user'],
                "token" => $response['token']
            ]
        ]);
    }

    public function me()
    {
        $response = $this->authService->me();
        return response()->json([
            "message" => "Success login",
            "data" => [
                "user" => $response['user'],
            ]
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json([
            "message" => "Success logout",
        ]);
    }

    public function refresh()
    {
        $token = $this->authService->refresh();
        return response()->json([
            "message" => "Success login",
            "data" => [
                "roken" => $token,
            ]
        ]);
    }
}
