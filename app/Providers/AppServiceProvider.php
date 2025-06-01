<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Session;
use MongoDB\Client as MongoClient;
use App\Extensions\MongoSessionHandler; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Session::extend('mongo', function ($app) {
            try {
                // Check if we have a direct connection string (for MongoDB Atlas)
                $connectionString = config('database.connections.mongodb.dsn');
                
                if (!empty($connectionString)) {
                    // Use MongoDB Atlas connection string
                    $dsn = $connectionString;
                } else {
                    // Build MongoDB connection string from individual config values (for local MongoDB)
                    $host = config('database.connections.mongodb.host', '127.0.0.1');
                    $port = config('database.connections.mongodb.port', 27017);
                    $username = config('database.connections.mongodb.username');
                    $password = config('database.connections.mongodb.password');
                    
                    // Build DSN
                    $dsn = 'mongodb://';
                    if (!empty($username) && !empty($password)) {
                        $dsn .= "{$username}:{$password}@";
                    }
                    $dsn .= "{$host}:{$port}";
                }
                
                $mongo = new MongoClient($dsn);
                $database = config('database.connections.mongodb.database', 'laravel');

                return new MongoSessionHandler($mongo, $database, 'sessions', config('session.lifetime'));
            } catch (\Exception $e) {
                // Fall back to file session handler if MongoDB fails
                return new \Illuminate\Session\FileSessionHandler(
                    app('files'), 
                    storage_path('framework/sessions'), 
                    config('session.lifetime')
                );
            }
        });
        Sanctum::usePersonalAccessTokenModel(\App\Models\PersonalAccessToken::class);
    }
}
