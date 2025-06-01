<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Custom Sanctum setup for Laravel 12 API authentication
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;

// Tell Sanctum to use our custom PersonalAccessToken model
Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware
        $middleware->alias([
            'api.verified' => \App\Http\Middleware\EnsureEmailIsVerifiedForApi::class,
            'auth.sanctum' => \App\Http\Middleware\SanctumMongoAuth::class, // Our custom MongoDB Sanctum auth middleware
        ]);
        
        // Apply middleware to API routes
        $middleware->group('api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Note: We're replacing the default Sanctum middleware with our custom one in the routes directly
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (\Throwable $e) {
            // Log authentication errors
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                \Illuminate\Support\Facades\Log::error('Authentication error: ' . $e->getMessage());
            }
        });
    })->create();
