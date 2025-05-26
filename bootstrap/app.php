<?php

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
        //
        $middleware->alias([
            'dashboard' => \App\Http\Middleware\DashboardMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'club' => \App\Http\Middleware\ClubMiddleware::class,
            'coach' => \App\Http\Middleware\CoachMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
