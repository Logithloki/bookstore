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

        // Find the cart item for the user and book
        $cartItem = Cart::where('user_id', $user->_id)
                        ->where('book_id', $id)
                        ->first();

        if ($cartItem) {
            // If the book is already in the cart, increment the quantity
            // Assuming 'quantity' field exists in the Cart model and is an integer.
            $cartItem->quantity = ($cartItem->quantity ?? 0) + 1; // Increment quantity, defaulting to 0 if null
            $cartItem->save();
            $message = 'Quantity updated in cart.';
        } else {
            // If the book is not in the cart, create a new cart item with quantity 1
            Cart::create([
                'user_id' => $user->_id,
                'book_id' => $id,
                'quantity' => 1, // Add with a quantity of 1
            ]);
            $message = 'Book added to cart successfully.';
        }

        // Dispatch Livewire event to refresh the cart display if the component is present
        // This assumes the CartManager component is listening for 'cartUpdated'
        
        // Since this is a redirect, Livewire will likely re-render the component on the next page load
        // But explicitly dispatching can be useful if the component is on the same page (e.g., a mini-cart)
        // $this->dispatch('cartUpdated'); // This might not be necessary/work directly from Controller

        return back()->with('success', $message);
    }
} 