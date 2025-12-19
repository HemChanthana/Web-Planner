<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="user@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user  = $request->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","phone"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User registered successfully")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'    => 'required|string|max:20',
            'address'  => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Success']);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get logged-in user profile",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Profile data")
     * )
     */
    public function profile(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users (Admin only)",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Users list"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(['users' => User::all()]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/update",
     *     summary="Update logged-in user",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="User updated")
     * )
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'phone'    => 'sometimes|string|max:20',
            'address'  => 'nullable|string|max:255',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->update([
            'name'     => $request->name ?? $user->name,
            'email'    => $request->email ?? $user->email,
            'phone'    => $request->phone ?? $user->phone,
            'address'  => $request->address ?? $user->address,
            'password' => $request->password
                ? Hash::make($request->password)
                : $user->password,
        ]);

        return response()->json(['message' => 'Updated', 'user' => $user]);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete user (Admin only)",
     *     tags={"Admin"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User deleted")
     * )
     */
    public function destroyUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        User::findOrFail($id)->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
