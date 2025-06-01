<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    /**
     * Store a newly created subscription in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if this user is already subscribed
        $existingSubscription = Newsletter::where('user_id', Auth::id())->first();
        
        if ($existingSubscription) {
            return redirect()->back()->with('info', 'You are already subscribed to our newsletter!');
        }

        // Create newsletter subscription
        Newsletter::create([
            'user_id' => Auth::id(),
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'You have successfully subscribed to our newsletter!');
    }

    /**
     * Unsubscribe from the newsletter
     */
    public function unsubscribe()
    {
        Newsletter::where('user_id', Auth::id())->delete();
        
        return redirect()->back()->with('success', 'You have been unsubscribed from our newsletter.');
    }
}
