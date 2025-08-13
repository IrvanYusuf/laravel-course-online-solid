<?php

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserAlreadyExistsException;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => JwtMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (UserAlreadyExistsException $e, $request) {
            return response()->json([
                'errors' => [
                    'email' => $e->getMessage()
                ]
            ], 400);
        });


        $exceptions->renderable(function (InvalidCredentialsException $e, $request) {
            return response()->json([
                'errors' => [
                    'credentials' => $e->getMessage()
                ]
            ], 400);
        });

        $exceptions->renderable(function (UnauthorizedException $e, $request) {
            return response()->json([
                'errors' => [
                    'credentials' => $e->getMessage()
                ]
            ], 400);
        });
    })->create();
