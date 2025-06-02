<?php

namespace App\Models;

use Mongodb\Laravel\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart';

    protected $fillable = [
        'user_id',
        'book_id',
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // MongoDB ObjectIds are handled automatically, no casting needed
    ];


    // Define relationship with the User who owns this cart item
    public function user()
    {
        // Cart (MongoDB) belongs to User (MySQL)
        // 'user_id' is the foreign key in this 'cart' collection that references MySQL users.id
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    // Define relationship with the Book in this cart item
    public function book()
    {
        // Both Cart and Book are in MongoDB, standard relationship
        // 'book_id' is the foreign key in this 'cart' collection
        return $this->belongsTo(\App\Models\Book::class, 'book_id', '_id');
    }
}