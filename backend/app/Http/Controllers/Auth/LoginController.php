<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('api')->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 24);


            return response()->json([
                'status_code' => 200,
                'message_id' => 'SUCCESSFULLY_LOGGED_IN',
                'message' => 'Successfulyy logged in',
                'user' => [
                    'email' => $user->email,
                    'token' => $token
                ]
            ])->withCookie($cookie);
        }

        return response()->json([
            'status_code' => 401,
            'message_id' => 'RECORD_NOT_FOUND',
            'message' => 'Credentials do not match to our records.',
            
        ]);
    }
}
