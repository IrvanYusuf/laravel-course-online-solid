<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/auth/login", [AuthController::class, "login"]);
Route::post("/auth/register", [AuthController::class, "register"]);




// category routes
Route::prefix("categories")->group(function () {
    Route::get("/", [CategoryController::class, "index"]);
    Route::get("/detail/{categoryId}", [CategoryController::class, "show"]);
});

Route::group(["middleware" => "auth"], function () {

    // auth routes
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

    // category routes
    Route::prefix("categories")->group(function () {
        Route::post("/", [CategoryController::class, "store"]);
        Route::patch("/{categoryId}", [CategoryController::class, "update"]);
        Route::delete("/{categoryId}", [CategoryController::class, "destroy"]);
    });
});
