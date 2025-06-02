<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add a book to the user's cart.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add($id)
    {
        $book = Book::findOrFail($id);
        
        $user = Auth::user();

        // Check if the book is already in the cart
        // Use MySQL user id (integer) instead of MongoDB _id
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('book_id', $id)
                        ->first();

        if ($cartItem) {
            // If the book is already in the cart, show message
            $message = 'Book is already in your cart.';
        } else {
            // If the book is not in the cart, create a new cart item
            // Use MySQL user id (integer) instead of MongoDB _id
            Cart::create([
                'user_id' => $user->id,
                'book_id' => $id,
            ]);
            $message = 'Book added to cart successfully.';
        }

        return back()->with('success', $message);
    }

}