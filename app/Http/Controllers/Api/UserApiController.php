<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class UserApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"[SUPERADMIN + ADMIN] Users"},
     *     summary="Menampilkan semua user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar user berhasil diambil"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Daftar user tidak ditemukan"
     *     )
     * )
     */
    public function index()
    {
        $users = UserResource::collection(User::all());

        if ($users->isEmpty()) {
            return ApiResponse::notFound('User not found');
        }
        return ApiResponse::success($users);
    }


    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"[SUPERADMIN] Users"},
     *     summary="Membuat user baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password", "user_type"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="user_type", type="string", enum={"Superadmin", "Admin"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User berhasil dibuat"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized (hanya superadmin yang dapat membuat user)"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'user_type' => 'required|in:Superadmin,Admin'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type']
        ]);

        return ApiResponse::created(new UserResource($user));
    }


    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"[SUPERADMIN] Users"},
     *     summary="Update user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string", nullable=true),
     *             @OA\Property(property="user_type", type="string", enum={"Superadmin", "Admin"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User berhasil diupdate"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User tidak ditemukan"
     *     ),
     *      @OA\Response(
     *         response=403,
     *         description="Unauthorized (hanya superadmin yang dapat mengupdate user)"
     *     )
     * )
     */
    public function update(Request $request, User $user)
    {
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'user_type' => 'sometimes|required|in:Superadmin,Admin'
        ]);

        $data = $request->only(['name', 'email', 'user_type']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return ApiResponse::updated($user);
    }

    /**
     *
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"[SUPERADMIN] Users"},
     *     summary="Hapus user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User berhasil dihapus"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized (hanya superadmin yang dapat menghapus user)",
     *     )
     * )
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $user->delete();

        return ApiResponse::deleted();
    }
}
