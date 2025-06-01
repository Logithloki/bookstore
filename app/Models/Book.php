<?php

namespace App\Models;

use Mongodb\Laravel\Eloquent\Model;

class Book extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'books';

    protected $fillable = [
        'title',
        'author',
        'category',
        'pages',
        'type', // 'Sell', 'Rental', 'Exchange'
        'condition', // 'New', 'Good', 'Fair', 'Poor'
        'year',
        'language',
        'cover', // Path or URL to the cover image
        'payment', // Assuming this stores the subscription plan the book was listed under ('Plan 1', 'Plan 2')
        'price', // For 'Sell'
        'rental_days', // For 'Rental'
        'exchange_category', // For 'Exchange'
        'user_id', // ID of the user who uploaded the book
        'stock', // Available quantity of this book
        'description', // Book description
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pages' => 'integer',
        'year' => 'integer',
        'price' => 'double', // Or 'decimal' if using MongoDB's Decimal128 type and the package supports it
        'rental_days' => 'integer',
    ];

    // Define relationship with the User who uploaded this book
    public function user()
    {
        // 'user_id' is the foreign key in this 'books' collection
        return $this->belongsTo(User::class, 'user_id');
    }
}