<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        // $authUser = $request->user(); 
        // $role = $authUser->user_role;
        $role = Auth::user()->user_role;

        $user_query = User::select();
        if($role != 'admin'){
            $user_query->where('user_role', 'user');
        }

        $users = $user_query->get();

        return response()->json($users, 200);
    }

    // to get user-detail on profile page
    public function userDetail(Request $request)
    {
        $authUser = $request->user(); 
        $id = $authUser->id;

        $userDetail = User::where('id', $id)->first();

        return response()->json($userDetail, 200);
    }

    // to get count of users
    public function count() {
        $count = User::count();

        return response()->json([
            'count' => $count
        ], 200);
    }

    public function logout(Request $request) {

        $authUser = $request->user(); 
        $role = $authUser->user_role;
        $userName = $authUser->user_name;

        Log::create([
            'model' => 'User',
            'name' => $userName,
            'actions' => 'Logout',
            'performed_by' => $role,
        ]);

        return response()->json([
            'message' => 'Logout log added'
        ], 200);
    }
}
