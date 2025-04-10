<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\UserNotFoundException;
use App\Exceptions\Auth\InvalidCredentialsException;

class UserService
{
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return [
            'data' => $user,
            'message' => 'User created successfully.',
        ];
    }

    public function getUserByEmail(string $email): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function authenticateUser(string $email, string $password): User
    {
        $user = $this->getUserByEmail($email);

        if (!Hash::check($password, $user->password)) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

    public function markEmailVerified(User $user): void
    {
        $user->email_verified_at = now();
        $user->save();
    }
}
