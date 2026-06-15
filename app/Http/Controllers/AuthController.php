<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/auth/login",
        summary: "User Authentication (Login)",
        description: "Autentikasi email & password user. Mengembalikan access_token (15 menit) dan refresh_token (7 hari).",
        tags: ["Authentication"]
    )]
    #[OA\RequestBody(
        required: true,
        description: "Kredensial login user",
        content: new OA\JsonContent(
            required: ["email", "password"],
            properties: [
                new OA\Property(property: "email", type: "string", format: "email", example: "admin@example.com"),
                new OA\Property(property: "password", type: "string", format: "password", example: "password123")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Autentikasi berhasil"
    )]
    #[OA\Response(
        response: 422,
        description: "Validasi gagal atau kredensial tidak cocok"
    )]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
            ]);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        // Access token (expires in 15 minutes)
        $accessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(15));
        
        // Refresh token (expires in 7 days)
        $refreshToken = $user->createToken('refresh_token', ['issue-access-token'], now()->addDays(7));

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 900, // 15 minutes in seconds
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                ]
            ]
        ]);
    }

    #[OA\Post(
        path: "/auth/refresh",
        summary: "Silent Token Refresh",
        description: "Memperbarui access_token yang kedaluwarsa menggunakan refresh_token.",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Token berhasil diperbarui"
    )]
    #[OA\Response(
        response: 401,
        description: "Refresh token kedaluwarsa atau tidak valid"
    )]
    #[OA\Response(
        response: 403,
        description: "Token tidak memiliki kemampuan issue-access-token"
    )]
    public function refresh(Request $request)
    {
        $user = $request->user();

        // Ensure the token used to authenticate has the refresh ability
        if (!$request->bearerToken() || !$user->tokenCan('issue-access-token')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid untuk memperbarui akses.'
            ], 403);
        }

        // Check if the current token is expired
        $currentToken = $user->currentAccessToken();
        if ($currentToken->expires_at && $currentToken->expires_at->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Refresh token telah kedaluwarsa. Silakan login kembali.'
            ], 401);
        }

        // Revoke the old access tokens (any token with access-api ability)
        $user->tokens()->where('name', 'access_token')->delete();

        // Issue new access token
        $newAccessToken = $user->createToken('access_token', ['access-api'], now()->addMinutes(15));

        return response()->json([
            'status' => 'success',
            'message' => 'Token berhasil diperbarui',
            'data' => [
                'access_token' => $newAccessToken->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 900,
            ]
        ]);
    }

    #[OA\Get(
        path: "/auth/me",
        summary: "Get Current User Profile",
        description: "Mengambil data profil user yang sedang masuk menggunakan access_token.",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Profil user berhasil diambil"
    )]
    #[OA\Response(
        response: 403,
        description: "Token tidak memiliki kemampuan access-api"
    )]
    public function me(Request $request)
    {
        $user = $request->user();

        // Ensure token has access ability
        if (!$user->tokenCan('access-api')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak valid untuk mengakses data ini.'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ]
        ]);
    }

    #[OA\Get(
        path: "/users",
        summary: "List All Users for Task Assignment",
        description: "Mengambil daftar semua user terdaftar untuk pilihan drop-down tugas.",
        tags: ["Users"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Daftar user berhasil diambil"
    )]
    public function users()
    {
        $users = Cache::remember('users_list_assignment', 3600, function () {
            return User::all(['id', 'name', 'email', 'role'])->toArray();
        });
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    #[OA\Post(
        path: "/auth/logout",
        summary: "User Logout",
        description: "Mencabut (revokes) semua token aktif untuk user tersebut.",
        tags: ["Authentication"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Logout berhasil"
    )]
    public function logout(Request $request)
    {
        // Revoke all tokens for the user
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}
