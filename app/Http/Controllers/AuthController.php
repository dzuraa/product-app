<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-new');
    }

    public function loginAjax(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'title' => 'Login gagal',
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // cek password manual
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'title' => 'Login gagal',
                'message' => 'Password salah'
            ], 401);
        }

        // login
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil'
        ]);
    }
}
