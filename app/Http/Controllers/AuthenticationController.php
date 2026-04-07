<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function store(Request $request){
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'gender' => 'required|in:male,female',
            'phone' => 'required',
            'password' => 'required|min:4|max:8'
        ]);

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'gender' => $fields['gender'],
            'phone' => $fields['phone'],
            'password' => Hash::make($fields['password'])
        ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'user created successfully!',
            'user' => $user
        ]);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:4|max:8'
        ]);

        $user = User::where('username', $fields['username'])->first();


        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response()->json([
                'status' => 'error',
                'mesage' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('loginToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'login successfully!',
            'token' => $token
        
        ]);
    }

    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'logout successfully!'
        ]);
}
}