<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticateMiddleware;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;

class SanctumMongoAuth extends BaseAuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Only apply this to API routes
        if (!str_starts_with($request->path(), 'api')) {
            return parent::handle($request, $next, ...$guards);
        }
        
        // Get the token from the request
        $token = $request->bearerToken();
        if (!$token) {
            throw new AuthenticationException('Unauthenticated - No token provided');
        }
        
        // Manually validate the token
        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken) {
            throw new AuthenticationException('Unauthenticated - Invalid token');
        }
        
        // Get the user
        $user = $accessToken->tokenable;
        if (!$user) {
            throw new AuthenticationException('Unauthenticated - User not found');
        }
          // Set the user in auth using the proper method in Laravel 12
        auth()->setUser($user);
        
        // Associate the token with the user
        $user->withAccessToken($accessToken);
        
        return $next($request);
    }
}
