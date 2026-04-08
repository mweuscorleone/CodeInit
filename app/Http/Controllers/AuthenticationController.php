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

    public function update(Request $request, $id){
             $user = User::find($id);

              if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'user not found'
            ]);
        }

         $fields = $request->validate([
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'username' => 'sometimes|unique:users,username',
            'email' => 'sometimes|unique:users,email',
            'gender' => 'sometimes|in:male,female',
            'phone' => 'sometimes',
            'password' => 'sometimes|min:4|max:8'
        ]);
        if(isset($reqest->$fields['password'])){
            $fields['password'] = Hash::make($fields['passowrd']);

        }

        $user->update($fields);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'user updated successfully!',
            'updated field' => array_keys($fields),
            'user' => $user
        ]);


    
    }

    public function destroy(Request $request, $id){
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'user not found'
            ]);

        }

        $user->delete();


        return response()->json([
            'status' => 'success',
            'message' => 'user deleted successfully'
        ]);
    }

    public function index(){
       $users = User::all();


       return $users;
    }

    public function userFind(Request $request, $id){
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'user is not found'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }
}