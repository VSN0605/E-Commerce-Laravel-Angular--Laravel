<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // to submit user form
    public function store(Request $request) {
        $request->validate([
            'user_name' => 'required|string',
            'user_email' => 'required|email|unique:users,user_email',
            'user_role' => 'required|string',
            'user_pass' => 'required|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $imageName = null;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        }

        User::create([
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_role' => $request->user_role,
            'password' => Hash::make($request->user_pass),
            'profile_image' => $imageName,
        ]);

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }

    // to get all users
    public function index(Request $request) {

        $user = $request->user();   // logged-in user
        $role = $user->user_role;

        // dd($user);
    
        $users = User::select(
            'id',
            'user_name',
            'user_email',
            'user_role',
            'profile_image',
            'created_at',
        )->get();

        return response()->json($users, 200);
    }

    // to get count of users
    public function count() {
        $count = User::count();

        return response()->json([
            'count' => $count
        ], 200);
    }
}
