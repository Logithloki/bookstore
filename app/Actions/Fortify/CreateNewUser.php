<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\UniqueMongoEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\ValidationException;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => $this->passwordRules(),
            'phonenumber' => ['required', 'string', 'max:20'],
            'location' => ['required', 'string', 'max:255'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        if (User::where('email', $input['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => ['The email address is already taken.'],
            ]);
}
        try {
            return User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'phonenumber' => $input['phonenumber'],
                'location' => $input['location'],
            ]);
        } catch (ValidationException $e) {
            if ($e->getCode() === 11000) {
                throw ValidationException::withMessages([
                    'email' => ['This email already exists.'],
                ]);
            }
            throw $e; // unexpected error
        }
    }
}
