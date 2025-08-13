<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/auth/login", [AuthController::class, "login"]);
Route::post("/auth/register", [AuthController::class, "register"]);

Route::group(["middleware" => "auth"], function () {
    Route::prefix("auth")->group(function () {
        Route::get("/me", [AuthController::class, "me"]);
        Route::get("/refresh", [AuthController::class, "refresh"]);
        Route::post("/logout", [AuthController::class, "logout"]);
    });

    // user routes
    Route::prefix("users")->group(function () {
        Route::get("/", [UserController::class, "getAllUsers"]);
        Route::post("/", [UserController::class, "createUser"]);
        Route::get("/detail/{userId}", [UserController::class, "getUserById"]);
        Route::patch("/{userId}", [UserController::class, "updateUser"]);
        Route::delete("/{userId}", [UserController::class, "deleteUser"]);
    });
});
