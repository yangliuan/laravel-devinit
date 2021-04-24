<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use EasyWeChat\Factory;

class WechatAuthController extends Controller
{
    public function miniprogramLogin(Request $request)
    {
        $request->validate([
            'code' => 'bail|required|string',
            'encryptedData' => 'bail|required|string',
            'iv' => 'bail|required|string',
        ]);

        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);

        $res_session = $app->auth->session($request->code);

        if (isset($res_session['errcode']) && $res_session['errcode'])
        {
            throw ValidationException::withMessages(['code' => $res_session['errcode'] . $res_session['errmsg']]);
        }

        try
        {
            $decryptedData = $app->encryptor->decryptData($res_session['session_key'], $request->iv, $request->encryptedData);
        }
        catch (\Exception $e)
        {
            throw ValidationException::withMessages(['errors' => ['encryptedData' => $e->getMessage()]]);
        }
        //dd($res_session, $decryptedData);
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

        return response()->json(['token' => $user->getToken()]);
    }

    public function logout(Request $request)
    {
        $request->user('api')->tokens()->delete();

        return response()->json();
    }
}
