<?php

namespace App\Models;

use Mongodb\Laravel\Eloquent\Model;

class Newsletter extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'newsletter';

    protected $fillable = [
        'user_id',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // MongoDB ObjectIds are handled automatically, no casting needed
    ];

    // Define relationship with the User who subscribed
    public function user()
    {
        // 'user_id' is the foreign key in this 'newsletter' collection
        return $this->belongsTo(User::class, 'user_id');
    }
}