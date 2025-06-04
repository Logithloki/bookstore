<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display the specified book.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        
        // Manually load the user from MySQL
        $user = null;
        if ($book->user_id) {
            $user = User::find($book->user_id);
        }
        
        // Load related books from the same category
        $relatedBooks = Book::where('category', $book->category)
            ->where('_id', '!=', $book->_id)
            ->take(4)
            ->get();
            
        return view('books.show', compact('book', 'user', 'relatedBooks'));
    }
} 