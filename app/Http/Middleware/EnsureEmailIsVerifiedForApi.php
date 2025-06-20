<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * API Email Verification Middleware
 * Similar to Laravel's 'verified' middleware but for API routes
 */
class EnsureEmailIsVerifiedForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || 
            ($request->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && 
             ! $request->user()->hasVerifiedEmail())) {
            return response()->json([
                'message' => 'Your email address is not verified.',
                'error' => 'email_not_verified'
            ], 403);
        }

        return $next($request);
    }
}
