<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VerificationCode;

class NotifyController extends Controller
{
    public function sendSms(Request $request)
    {
        $request->validate([
            'mobile' => 'bail|required|numeric|phone:CN'
        ], [
            'mobile.phone' => '请输入正确的手机号'
        ]);

        VerificationCode::create($request->input('mobile'));

        return response()->json();
    }
}
