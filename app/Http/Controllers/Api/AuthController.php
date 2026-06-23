<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
            ], 422);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        $accessToken = $user->createToken('access_token', ['access-api']);
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$user->tokenCan('issue-access-token')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid untuk memperbarui akses.',
            ], 403);
        }

        // Revoke old access tokens
        $user->tokens()->where('name', 'access_token')->delete();

        $newAccessToken = $user->createToken('access_token', ['access-api']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $newAccessToken->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 900,
            ],
        ]);
    }
}

