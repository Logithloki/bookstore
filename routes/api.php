<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Http\Controllers\API\AuthController;
use Illuminate\Validation\Rules;

// Authentication routes using native Sanctum
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

    // Book management routes
    Route::get('/my-books', [BookController::class, 'myBooks']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'addItem']);
        Route::post('/update', [CartController::class, 'updateQuantity']); 
        Route::post('/remove', [CartController::class, 'removeItem']);
        Route::post('/clear', [CartController::class, 'clear']);
    });
});

// Public routes (no authentication required)
Route::get('/books/latest', [BookController::class, 'latest']);
Route::get('/books/exchange', [BookController::class, 'exchange']);
Route::get('/books/used', [BookController::class, 'used']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);

