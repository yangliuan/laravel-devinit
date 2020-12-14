<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\ApiRequest;

class AdminRequest extends ApiRequest
{
    public function rules()
    {
        $id = basename($this->path());

        return [
            'name' => [
                'bail', 'required', 'string', 'max:20',
                Rule::unique('admins', 'name')->ignore($id),
            ],
            'account' => [
                'bail', 'required', 'string', 'max:20',
                Rule::unique('admins', 'account')->ignore($id),
            ],
            'password' => 'bail|nullable|string',
            'mobile' => [
                'bail', 'required', 'string', 'max:11',
                Rule::unique('admins', 'mobile')->ignore($id),
            ],
            'group_id' => 'bail|required|integer|exists:admin_groups,id',
            'status' => 'bail|required|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '管理员名字已存在',
            'mobile.unique' => '管理员手机号已存在',
            'group_id.exists' => '管理组不存在'
        ];
    }
}
