<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch authenticated user's books using MySQL user.id
        $userBooks = Book::where('user_id', Auth::id())->get();
        return view('admin.books.index', compact('userBooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user has a subscription plan
        if (empty(Auth::user()->payment)) {
            return redirect()->route('packages')->with('error', 'You need to subscribe to a plan before you can upload books.');
        }
        
        return view('admin.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:9999',
            'pages' => 'required|integer|min:1',
            'language' => 'required|string|max:100',
            'condition' => 'required|in:New,Good,Fair,Poor',
            'for' => 'required|in:Sell,Rental,Exchange',
            'price' => 'required_if:for,Sell|nullable|numeric|min:0',
            'rental_days' => 'required_if:for,Rental|nullable|integer|min:1',
            'exchange_category' => 'required_if:for,Exchange|nullable|string|max:255',
            'cover' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $data = $request->only([
            'title',
            'author',
            'category',
            'year',
            'pages',
            'language',
            'condition',
            'for',
            'payment', // Assuming payment is part of the form or a default
        ]);

        $data['user_id'] = Auth::id();
        $data['price'] = $request->for === 'Sell' ? $request->price : null;
        $data['rental_days'] = $request->for === 'Rental' ? $request->rental_days : null;
        $data['exchange_category'] = $request->for === 'Exchange' ? $request->exchange_category : null;
        $data['payment'] = $request->input('payment', 'BASIC'); // Get payment from input or default


        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('message', 'Book added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // This method is typically for displaying a single resource
        // but we have a separate public books.show route/controller for users.
        // If needed for admin view, implement here.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::where('_id', $id)->firstOrFail();

        // Ensure the authenticated user owns the book using MySQL user.id
        if ($book->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:9999',
            'pages' => 'required|integer|min:1',
            'language' => 'required|string|max:100',
            'condition' => 'required|in:New,Good,Fair,Poor',
            'for' => 'required|in:Sell,Rental,Exchange',
            'price' => 'required_if:for,Sell|nullable|numeric|min:0',
            'rental_days' => 'required_if:for,Rental|nullable|integer|min:1',
            'exchange_category' => 'required_if:for,Exchange|nullable|string|max:255',
            'cover' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $book = Book::where('_id', $id)->firstOrFail();

        // Ensure the authenticated user owns the book using MySQL user.id
        if ($book->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        $data = $request->only([
            'title',
            'author',
            'category',
            'year',
            'pages',
            'language',
            'condition',
            'for',
            'payment',
        ]);

        $data['price'] = $request->for === 'Sell' ? $request->price : null;
        $data['rental_days'] = $request->for === 'Rental' ? $request->rental_days : null;
        $data['exchange_category'] = $request->for === 'Exchange' ? $request->exchange_category : null;
        $data['payment'] = $request->input('payment', $book->payment); // Get payment from input or keep existing

        if ($request->hasFile('cover')) {
            // Delete old cover if it exists
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('message', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::where('_id', $id)->firstOrFail();

        // Ensure the authenticated user owns the book using MySQL user.id
        if ($book->user_id !== Auth::id()) {
            abort(403); // Forbidden
        }

        // Delete the cover image if it exists
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('message', 'Book deleted successfully!');
    }
}
