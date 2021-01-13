<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Requests\RegisterOrLoginRequest;
use App\Models\User;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function registerOrLogin(RegisterOrLoginRequest $request)
    {
        $user = User::firstOrCreate(['mobile' => $request->input('mobile')]);
        $token = $user->getToken();

        return response()->json(['token_type' => 'bearer', 'token' => $token]);
    }

    public function loginOut(Request $request)
    {
        $request->user('api')->tokens()->delete();

        return response()->json();
    }
}
