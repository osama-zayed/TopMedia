<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $token = null;

            // Check if a token name is provided in the request
            if ($request->has('token_name') && !is_null($request->token_name)) {
                $token = $request->user()->createToken($request->token_name);
            } else {
                $token = $request->user()->createToken('auth_token');
            }

            return response()->json([
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ]);
        }

        // If authentication fails, return a 401 Unauthorized response
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    public function register(Request $request)
    {
        // Validate the registration request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the response
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }
    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        // Return a success response
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function editUser(Request $request)
    {
        // Validate the update request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Update the user's information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Return a success response
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }
    public function me()
    {
        $data = auth('sanctum')->user();
        return self::responseSuccess($data);
    }
    
}
