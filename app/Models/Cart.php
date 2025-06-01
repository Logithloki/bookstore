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
        // 'user_id' is the foreign key in this 'cart' collection
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define relationship with the Book in this cart item
    public function book()
    {
        // 'book_id' is the foreign key in this 'cart' collection
        return $this->belongsTo(Book::class, 'book_id');
    }
}