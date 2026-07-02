<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $referenceNo = 'REF-' . strtoupper(Str::random(8));

        User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => bcrypt($validated['password']),
            'reference_no' => $referenceNo,
        ]);

        return response()->json([
            'message'      => 'Registration successful.',
            'reference_no' => $referenceNo,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'reference_no' => ['required', 'string'],
        ]);

        $user = User::where('reference_no', $request->reference_no)->first();

        if (! $user) {
            return response()->json([
                'message' => 'Invalid reference number.'
            ], 401);
        }

        $token = $user->createToken('chatbot')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }
}
