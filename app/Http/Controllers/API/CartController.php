<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{    /**
     * Display the user's cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $cart = Cart::where('user_id', $user->id)
            ->with('book')
            ->get();
            
        $total = $cart->sum(function ($item) {
            return $item->book->price;
        });
        
        return response()->json([
            'items' => $cart,
            'total' => $total,
            'count' => $cart->count()
        ]);
    }
      /**
     * Add an item to the cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,_id',
        ]);
        
        $user = $request->user();
        $bookId = $request->book_id;
        
        
        // Check if item already exists in cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->first();
            
        if ($cartItem) {
            return response()->json([
                'message' => 'Book already in cart'
            ], 400);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'book_id' => $bookId
            ]);
        }
        
        return response()->json([
            'message' => 'Book added to cart successfully'
        ]);
    }    /**
     * This method is kept for API compatibility but we no longer support quantity
     * as per the simplified cart requirements
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,_id',
        ]);
        
        $user = $request->user();
        $cartItem = Cart::where('_id', $request->cart_id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return response()->json([
            'message' => 'Book is in the cart',
            'cart_item' => $cartItem->load('book')
        ]);
    }
    
    /**
     * Remove item from cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItem(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,_id',
        ]);
        
        $user = $request->user();
        
        $deleted = Cart::where('_id', $request->cart_id)
            ->where('user_id', $user->id)
            ->delete();
            
        if ($deleted) {
            return response()->json([
                'message' => 'Item removed from cart'
            ]);
        }
        
        return response()->json([
            'message' => 'Item not found in cart'
        ], 404);
    }
    
    /**
     * Clear cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        
        Cart::where('user_id', $user->id)->delete();
        
        return response()->json([
            'message' => 'Cart cleared successfully'
        ]);
    }
}
