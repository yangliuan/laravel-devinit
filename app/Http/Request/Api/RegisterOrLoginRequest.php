<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;
use App\Services\VerificationCode;

class RegisterOrLoginRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'mobile' => 'bail|required|phone:CN',
            'code' => [
                'bail', 'nullable', 'required_if:login_party,mobile', 'numeric',
                function ($attribute, $value, $fail)
                {
                    if (!VerificationCode::validate($this->input('mobile'), $value))
                    {
                        return $fail('验证码错误');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'mobile.phone' => '请输入正确的手机号'
        ];
    }
}
