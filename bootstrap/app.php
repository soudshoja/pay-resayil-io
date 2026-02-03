<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/platform.php'));

            Route::middleware('web')
                ->group(base_path('routes/client.php'));

            Route::middleware('web')
                ->group(base_path('routes/sales.php'));

            Route::middleware('web')
                ->group(base_path('routes/accountant.php'));

            Route::middleware('web')
                ->group(base_path('routes/agent.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
            'agency.active' => \App\Http\Middleware\EnsureAgencyActive::class,
            'platform.owner' => \App\Http\Middleware\EnsurePlatformOwner::class,
            'client.admin' => \App\Http\Middleware\EnsureClientAdmin::class,
            'sales.person' => \App\Http\Middleware\EnsureSalesPerson::class,
            'accountant' => \App\Http\Middleware\EnsureAccountant::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
            'agent.authenticated' => \App\Http\Middleware\EnsureAgentAuthenticated::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
