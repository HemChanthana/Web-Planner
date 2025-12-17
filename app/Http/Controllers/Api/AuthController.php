<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller

{
    

public function login(Request $request)
    {
     
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

     
        $user = $request->user();

        
        $token = $user->createToken('api-token')->plainTextToken;

       
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function Register(Request $request)
    {
         $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


         $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],    
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],

        ]);

        $user = User::create([
             'name' => $request->name,
            'email' => $request->email,
             'phone' => $request->phone,
             'address' => $request->address,
             'password' => Hash::make($request->password),
       
        ]);

          return response()->json([
            'message' => "Success"
        ]);
    }

    public function profile(Request $request)
{
    return response()->json([
        'user' => $request->user()
    ]);
}


    public function index(Request $request){
        
          if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    return response()->json([
        'users' => User::all()
    ]);
    }


    public function update(Request $request){
        $user = $request->user();

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'phone' => 'sometimes|string|max:20',
        'address' => 'nullable|string|max:255',
        'password' => 'nullable|confirmed|min:8',
    ]);

    $user->update([
        'name' => $request->name ?? $user->name,
        'email' => $request->email ?? $user->email,
        'phone' => $request->phone ?? $user->phone,
        'address' => $request->address ?? $user->address,
        'password' => $request->password
            ? Hash::make($request->password)
            : $user->password,
    ]);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
    }
public function destroyUser($id)
{
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted']);
}


    }
