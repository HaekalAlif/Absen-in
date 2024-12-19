<?php

use App\Http\Middleware\RoleManager;
use App\Http\Middleware\JwtTokenMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'rolemanager' => RoleManager::class,
            'jwt.token' => JwtTokenMiddleware::class, // Menambahkan middleware JwtTokenMiddleware
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
