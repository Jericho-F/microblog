<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;

class RegisterController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'status_code' => 200,
            'message_id' => 'SUCCESS',
            'message' => 'Registered successfully',
            'user' => $user,
            'token' => $token
        ]);
    }
}
