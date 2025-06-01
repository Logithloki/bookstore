<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Models\NewAccessToken;
use App\Models\PersonalAccessToken;

trait HasMongoApiTokens
{
    public function createToken(string $name, array $abilities = ['*']): NewAccessToken
    {
        $plainTextToken = Str::random(40);

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'tokenable_type' => static::class,
        ]);

        return new NewAccessToken($token, $token->_id . '|' . $plainTextToken);
    }    public function tokens()
    {
        return $this->hasMany(PersonalAccessToken::class, 'tokenable_id', '_id');
    }

    /**
     * The access token the user is using for the current request.
     *
     * @var \App\Models\PersonalAccessToken
     */
    protected $accessToken;

    /**
     * Get the access token currently associated with the user.
     *
     * @return \App\Models\PersonalAccessToken
     */
    public function currentAccessToken()
    {
        return $this->accessToken;
    }    /**
     * Set the current access token for the user.
     *
     * @param  \App\Models\PersonalAccessToken  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCan(string $ability)
    {
        return $this->accessToken && $this->accessToken->can($ability);
    }

    /**
     * Determine if the current API token does not have a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCant(string $ability)
    {
        return ! $this->tokenCan($ability);
    }
}
