<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; // Import the Book model

class HomeController extends Controller
{
    public function index()
    {
        // Fetch latest books (assuming sorting by creation date or a timestamp)
        // If you have a 'created_at' field (automatically added by Eloquent timestamps)
        $latestBooks = Book::orderBy('created_at', 'desc')->limit(6)->get();

        // If you don't have timestamps, and rely on a different field for 'latest', adjust here.
        // Example if 'id' was used (less common in production MongoDB without specific index):
        // $latestBooks = Book::orderBy('_id', 'desc')->limit(6)->get();


        // Fetch exchange books
        $exchangeBooks = Book::where('type', 'Exchange')->limit(6)->get(); // Limit to 6 as in your original logic

        // Fetch used books
        $usedBooks = Book::where('condition', 'Fair')->limit(6)->get(); // Limit to 6

        // Return the view and pass the data
        return view('homepage', [
            'latestBooks' => $latestBooks,
            'exchangeBooks' => $exchangeBooks,
            'usedBooks' => $usedBooks,
            'currentPage' => 'home', // Pass the current page for the navbar component
        ]);
    }

    // Method for the About Us page
    public function aboutUs()
    {
        // Simply return the view. The data is static HTML.
        return view('about-us', ['currentPage' => 'about']);
    }
}
