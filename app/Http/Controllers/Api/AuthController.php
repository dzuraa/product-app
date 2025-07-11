<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register user baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registrasi berhasil",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Kirim email sambutan (pakai queue jika ingin)
        SendWelcomeEmail::dispatch($user);

        // Login otomatis setelah register
        // $token = JWTAuth::fromUser($user);

        return ApiResponse::registerSuccess($user);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login user dan dapatkan token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@mail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Email atau password salah"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return ApiResponse::error('Email atau password salah', 401);
        }

        return ApiResponse::loginSuccess($token, Auth::user());
    }


    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Token tidak valid",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized (token tidak diberikan atau expired)"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken());
            return response()->json(['message' => 'Logout berhasil']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Gagal logout, token tidak valid'], 400);
        }
    }
}
