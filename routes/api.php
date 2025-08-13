<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/auth/login", [AuthController::class, "login"]);
Route::post("/auth/register", [AuthController::class, "register"]);

Route::group(["middleware" => "auth", "prefix" => "auth"], function () {
    Route::get("/me", [AuthController::class, "me"]);
    Route::get("/refresh", [AuthController::class, "refresh"]);
    Route::post("/logout", [AuthController::class, "logout"]);
});
