<?php

namespace App\Models;

use Laravel\Sanctum\NewAccessToken as SanctumNewAccessToken;

/**
 * Custom NewAccessToken class for MongoDB compatibility
 */
class NewAccessToken extends SanctumNewAccessToken
{
    /**
     * Create a new access token result.
     *
     * @param  \App\Models\PersonalAccessToken  $accessToken
     * @param  string  $plainTextToken
     * @return void
     */
    public function __construct($accessToken, string $plainTextToken)
    {
        $this->accessToken = $accessToken;
        $this->plainTextToken = $plainTextToken;
    }
}
