<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\ApiRequest;
use App\Models\AdminGroups;

class AdminRequest extends ApiRequest
{
    public function rules()
    {
        $id = basename($this->path());

        switch ($this->method())
        {
            case 'POST':
                // CREATE
                {
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
            case 'PUT':
            case 'PATCH':
                // UPDATE
                {
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
                        'group_id' => [
                            'bail', 'required', 'integer',
                            function ($attribute, $value, $fail)
                            {
                                if ($value > 0)
                                {
                                    if (AdminGroups::where('id', $value)->count() === 0)
                                    {
                                        return $fail('管理组不存在');
                                    }
                                }
                            }
                        ],
                        'status' => 'bail|required|integer|in:0,1',
                    ];
                }
            case 'GET':
            case 'DELETE':
            default:
                {
                    return [];
                }
        }
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
