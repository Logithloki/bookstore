<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Show subscription plans
     */
    public function index()
    {
        return view('packages');
    }

    /**
     * Process subscription selection
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:BASIC,PREMIUM',
        ]);

        $user = Auth::user();
        $user->payment = $request->plan;
        $user->save();

        return redirect()->route('homepage')->with('success', 'Subscription plan selected successfully!');
    }

    /**
     * Check if user has active subscription
     */
    public function checkSubscription()
    {
        $user = Auth::user();
        return response()->json([
            'has_subscription' => !empty($user->payment),
            'plan' => $user->payment ?? null,
        ]);
    }

    /**
     * Middleware to check if user has subscription before allowing book uploads
     */
    public function hasSubscription()
    {
        if (empty(Auth::user()->payment)) {
            return false;
        }
        return true;
    }
}
