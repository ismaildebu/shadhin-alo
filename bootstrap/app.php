<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/authorization.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {
            require base_path('routes/auth.php');
            require base_path('routes/user-management.php');
            require base_path('routes/settings.php');
            require base_path('routes/audit.php');
            require base_path('routes/articles.php');
            require base_path('routes/comments.php');
        },
    )

        ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        $schedule->command('articles:publish-scheduled')->everyMinute();
    })

    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();


