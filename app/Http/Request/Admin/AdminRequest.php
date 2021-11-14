<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\ApiRequest;
use App\Models\AdminGroups;

class AdminRequest extends ApiRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                // CREATE
                {
                    return [
                        'name' => [
                            'bail', 'required', 'string', 'max:20',
                            Rule::unique('admins', 'name'),
                        ],
                        'account' => [
                            'bail', 'required', 'string', 'max:20',
                            Rule::unique('admins', 'account'),
                        ],
                        'password' => 'bail|nullable|string',
                        'mobile' => [
                            'bail', 'required', 'string', 'max:11',
                            Rule::unique('admins', 'mobile'),
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
                        'id' => [
                            function ($attribute, $value, $fail) {
                                if ($this->getRestFullRouteId() && $this->getRestFullRouteId() === 1) {
                                    if ($this->is('admin/admin/*') && $this->getRestFullRouteId() !== $this->user('admin')->id) {
                                        return $fail('普通管理员无法修改系统管理员');
                                    } elseif ($this->is('admin/admin/status/*')) {
                                        return $fail('系统管理员无法被禁用');
                                    }
                                }
                            }
                        ],
                        'name' => [
                            'bail', 'required', 'string', 'max:20',
                            Rule::unique('admins', 'name')->ignore($this->getRestFullRouteId()),
                        ],
                        'account' => [
                            'bail', 'required', 'string', 'max:20',
                            Rule::unique('admins', 'account')->ignore($this->getRestFullRouteId()),
                        ],
                        'password' => 'bail|nullable|string',
                        'mobile' => [
                            'bail', 'required', 'string', 'max:11',
                            Rule::unique('admins', 'mobile')->ignore($this->getRestFullRouteId()),
                        ],
                        'group_id' => [
                            'bail', 'required', 'integer',
                            function ($attribute, $value, $fail) {
                                if ($value > 0) {
                                    if (AdminGroups::where('id', $value)->count() === 0) {
                                        return $fail('管理组不存在');
                                    }
                                }
                            }
                        ],
                        'status' => 'bail|required|integer|in:0,1',
                    ];
                }
            case 'GET':
                {
                    return [
                        'page' => 'bail|required|integer|min:1',
                        'per_page' => 'bail|required|integer|min:1',
                    ];
                }
            case 'DELETE':
                {
                    return [
                        'id' => [
                            function ($attribute, $value, $fail) {
                                if ($this->getRestFullRouteId() && $this->getRestFullRouteId() === 1) {
                                    return $fail('无法删除系统管理员');
                                }
                            }
                        ]
                    ];
                }
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
