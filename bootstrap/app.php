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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'super-admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'manager' => \App\Http\Middleware\ManagerMiddleware::class,
            'active-account' => \App\Http\Middleware\ActiveAccountMiddleware::class,
            'verified-phone' => \App\Http\Middleware\VerifiedPhoneMiddleware::class,
            'subscription' => \App\Http\Middleware\SubscriptionMiddleware::class,
            'card-owner' => \App\Http\Middleware\CardOwnerMiddleware::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->preventRequestsDuringMaintenance([
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
