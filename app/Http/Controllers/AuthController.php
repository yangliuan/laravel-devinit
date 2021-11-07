<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\RegisterOrLoginRequest;
use App\Models\User;
use EasyWeChat\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function mobile(RegisterOrLoginRequest $request)
    {
        $user = User::firstOrCreate(['mobile' => $request->input('mobile')]);
        $token = $user->getToken();

        return response()->json(['token_type' => 'Bearer', 'token' => $token]);
    }

    public function miniprogram(Request $request)
    {
        $request->validate([
            'code' => 'bail|required|string',
            'encryptedData' => 'bail|required|string',
            'iv' => 'bail|required|string',
        ]);

        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);

        $res_session = $app->auth->session($request->code);

        if (isset($res_session['errcode']) && $res_session['errcode']) {
            throw ValidationException::withMessages(['code' => $res_session['errcode'] . $res_session['errmsg']]);
        }

        try {
            $decryptedData = $app->encryptor->decryptData($res_session['session_key'], $request->iv, $request->encryptedData);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['errors' => ['encryptedData' => $e->getMessage()]]);
        }

        $user = User::updateOrCreate([
            'wechat_openid' => $res_session['openid']
        ], [
            'name' => $decryptedData['nickName'],
            'avatar' => $decryptedData['avatarUrl'],
            'sex' => $decryptedData['gender'],
            'province' => $decryptedData['province'],
            'city' => $decryptedData['city'],
            'area' => $decryptedData['country'],
        ]);

        return response()->json(['token_type' => 'Bearer', 'token' => $user->getToken()]);
    }

    public function logout(Request $request)
    {
        $request->user('api')->tokens()->where('name', 'api')->delete();

        return response()->json();
    }
}
