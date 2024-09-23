<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
           'name' => $validated['name'],
           'email' => $validated['email'],
           'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'User registered successfully!'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user(),
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function user(Request $request)
    {
        return response()->json(['user' => Auth::user()]);
    }
}
