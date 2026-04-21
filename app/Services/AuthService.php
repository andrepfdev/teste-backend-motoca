<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * @return array{user: UserResource, token: string}
     */
    public function register(array $data): array
    {
        $user = User::create($data);

        return [
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }

    /**
     * @return array{user: UserResource, token: string}|null
     */
    public function login(array $data): ?array
    {
        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();

        return [
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
