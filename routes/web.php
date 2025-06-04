<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\HomeController;
use App\Models\User;
use App\Models\Book;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/homepage', function () {
        return view('homepage');
    })->name('homepage');
});

Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');

Route::middleware('auth')->post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken('api-token');
    return ['token' => $token->plainTextToken];
});

// Public routes (no authentication required)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');



    // Admin book management routes
    Route::resource('admin/books', AdminBookController::class)->names('admin.books');
    
    // Friendly route for adding books
    Route::get('/add-book', [AdminBookController::class, 'create'])->name('add-book');
    
    // Friendly route for editing books
    Route::get('/edit-book/{id}', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('/edit-book/{id}', [AdminBookController::class, 'update'])->name('books.update');
});

Route::middleware(['auth'])->group(function () {
    // Account page route
    Route::get('/account', function () {
        return view('account');
    })->name('account.show');
});


// Subscription routes
Route::get('/packages', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('packages');
Route::middleware('auth')->post('/subscribe', [App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name('subscribe');

// Newsletter routes
Route::middleware('auth')->post('/newsletter', [App\Http\Controllers\NewsletterController::class, 'store'])->name('newsletter.store');
Route::middleware('auth')->get('/newsletter/unsubscribe', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');


Route::get('/search', function () {
    return view('search');
})->name('search');