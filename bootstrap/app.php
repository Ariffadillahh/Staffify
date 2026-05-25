<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias untuk middleware role
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // ATUR REDIRECT DI SINI
        $middleware->redirectTo(
            guests: '/login',      // Jika belum login mau ke admin, lempar ke sini
            users: '/admin/proker' // Jika sudah login mau ke login, lempar ke sini
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
