<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_pass' => 'required',
        ]);

        $user = User::where('user_email', $request->user_email)->first();

        if(!$user || !Hash::check($request->user_pass, $user->password)) {
            return response()->json([
                'message' => 'Invalid username or Password'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $auth_id =  Auth::id();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
            'authID' => $auth_id
            // 'user_role' => $user->user_role,
        ], 200);
    }
}
