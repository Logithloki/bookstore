<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Book::query();
        
        // Filter by book type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by condition
        if ($request->has('condition')) {
            $query->where('condition', $request->condition);
        }
        
        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        // Search books by title or author
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $books = $query->paginate($perPage);
        
        return response()->json($books);
    }

    /**
     * Display the specified book.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        
        // Load related books
        $relatedBooks = Book::where('category', $book->category)
            ->where('_id', '!=', $book->_id)
            ->take(5)
            ->get();
            
        return response()->json([
            'book' => $book,
            'related_books' => $relatedBooks
        ]);
    }
    
    /**
     * Get the latest books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        $latestBooks = Book::latest()->take(10)->get();
        
        return response()->json($latestBooks);
    }
    
    /**
     * Get books for exchange.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange()
    {
        $exchangeBooks = Book::where('type', 'Exchange')->take(10)->get();
        
        return response()->json($exchangeBooks);
    }
    
    /**
     * Get used books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function used()
    {
        $usedBooks = Book::where('condition', 'Used')->take(10)->get();
        
        return response()->json($usedBooks);
    }
    
    /**
     * Store a newly created book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'type' => 'required|in:Sell,Rental,Exchange',
            'condition' => 'required|in:New,Good,Fair,Poor',
            'price' => 'required_if:type,Sell|numeric|nullable',
            'rental_days' => 'required_if:type,Rental|integer|nullable',
            'exchange_category' => 'required_if:type,Exchange|string|nullable',
            'pages' => 'nullable|integer',
            'year' => 'nullable|integer',
            'language' => 'nullable|string',
            'cover' => 'nullable|string',
            'description' => 'nullable|string'
        ]);
        
        // Get the authenticated user
        $user = $request->user();
        
        // Check if the user has an active subscription
        if (empty($user->payment)) {
            return response()->json([
                'message' => 'You need to subscribe to a plan before uploading books.',
                'redirect' => route('packages')
            ], 403);
        }
        
        // Create the book with the user_id and subscription plan
        $book = Book::create(array_merge(
            $request->all(),
            [
                'user_id' => $user->id,
                'payment' => $user->payment // Store the subscription plan with the book
            ]
        ));
        
        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book
        ], 201);
    }
    
    /**
     * Update the specified book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        // Verify ownership
        if ($request->user()->id !== $book->user_id) {
            return response()->json([
                'message' => 'You do not have permission to update this book'
            ], 403);
        }
        
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'type' => 'sometimes|in:Sell,Rental,Exchange',
            'condition' => 'sometimes|in:New,Good,Fair,Poor',
            'price' => 'required_if:type,Sell|numeric|nullable',
            'rental_days' => 'required_if:type,Rental|integer|nullable',
            'exchange_category' => 'required_if:type,Exchange|string|nullable',
            'pages' => 'nullable|integer',
            'year' => 'nullable|integer',
            'language' => 'nullable|string',
            'cover' => 'nullable|string',
            'description' => 'nullable|string'
        ]);
        
        $book->update($request->all());
        
        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }
    
    /**
     * Remove the specified book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        // Verify ownership
        if ($request->user()->id !== $book->user_id) {
            return response()->json([
                'message' => 'You do not have permission to delete this book'
            ], 403);
        }
        
        // Check if the book is in any user's cart
        $inCart = Cart::where('book_id', $id)->exists();
        
        if ($inCart) {
            return response()->json([
                'message' => 'Cannot delete book as it is in one or more user carts'
            ], 400);
        }
        
        $book->delete();
        
        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }
    
    /**
     * Get books uploaded by the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myBooks(Request $request)
    {
        $books = Book::where('user_id', $request->user()->id)->get();
        
        return response()->json($books);
    }
}
