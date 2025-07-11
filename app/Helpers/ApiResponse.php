<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'success', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function created($data = null, $message = 'Data created successfully')
    {
        return self::success($data, $message, 201);
    }

    public static function updated($data = null, $message = 'Data updated successfully')
    {
        return self::success($data, $message, 200);
    }

    public static function deleted($message = 'Data deleted successfully')
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => null
        ], 200);
    }

    public static function error($message = 'Something went wrong', $code = 400, $data = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function notFound($message = 'Not Found')
    {
        return self::error($message, 404);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }

    public static function validation($errors, $message = 'Validation error')
    {
        return self::error($message, 422, $errors);
    }

    public static function loginSuccess($token, $user)
    {
        $userResponse = $user->only(['id', 'name', 'email', 'user_type']);

        return self::success([
            'token' => $token,
            'user' => $userResponse
        ], 'Login successful');
    }

    public static function registerSuccess($user)
    {
        $userResponse = $user->only(['id', 'name', 'email', 'user_type']);

        return self::success($userResponse, 'Registration successful');
    }
}
