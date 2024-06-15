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
            $user = Auth::user();
    
            // Create a new token for the user
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Remember the user
            if ($request->remember) {
                Auth::login($user, true);
            }
    
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    
        // If authentication fails, return a 401 Unauthorized response
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    public function me()
    {
    //   $data =User::where('id',->id);
      return self::responseSuccess("dd");
    }
}