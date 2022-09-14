<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['authUser', 'logout']);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Password or email maybe wrong.',
                ]
            ], 400);
        }

        $token = $user->createToken('token_sanctum')->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response(null, 204);
    }

    public function authUser(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
