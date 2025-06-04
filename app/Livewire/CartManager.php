<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class CartManager extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $itemCount = 0;

    protected $listeners = ['cartUpdated' => 'refreshCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())
                ->with('book')
                ->get();

            $this->cartItems = $cart->map(function ($item) {
                return [
                    'id' => $item->_id,
                    'book_id' => $item->book_id,
                    'title' => $item->book->title,
                    'author' => $item->book->author,
                    'price' => $item->book->price,
                    'type' => $item->book->type,
                    'cover' => $item->book->cover,
                ];
            })->toArray();

            $this->calculateTotals();
        }
    }

    public function removeItem($cartId)
    {
        $cartItem = Cart::find($cartId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            $this->refreshCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
        $this->refreshCart();
        $this->dispatch('cartUpdated');
    }

    protected function calculateTotals()
    {
        $this->total = collect($this->cartItems)->sum('price');
        $this->itemCount = count($this->cartItems);
    }

    public function render()
    {
        return view('livewire.cart-manager');
    }
} 