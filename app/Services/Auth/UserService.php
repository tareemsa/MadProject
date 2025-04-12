<?php

namespace App\Services\Auth;
use App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\CustomException;

class UserService
{
    public function createUser(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!$user) {
            throw new CustomException('Failed to create user', 500);
        }

        return [
            'user' => $user,
            'message' => 'User registered successfully.',
            'code' => 200,
        ];
        
    }
    public function getUserByEmail(string $email): User
{
    $user = User::where('email', $email)->first();

    if (! $user) {
        throw new CustomException('User not found.', 404);
    }

    return $user;
}

}
