<?php

/*
 * This file is part of the yangliuan/laradevtools.
 *
 * (c) yangliuan <yangliuancn@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 获取路由末尾的参数
     *
     * @return mixed
     */
    public function getRestFullRouteId()
    {
        $id = basename($this->path());

        if (is_numeric($id)) {
            return (int) $id;
        }

        return null;
    }

    /**
     * 过滤null值，并且合并默认值
     *
     * @param array $default 默认值数组，用于选填的字段
     * @param string $request_method 获取数据方法 all only except
     * @param mixed $keys
     * @return array
     */
    public function filter(array $default = [], $request_method = 'all', $keys = null)
    {
        if (!in_array($request_method, ['all','only','except'])) {
            return [];
        }
        return array_filter($this->$request_method($keys), function ($value) {
            return !is_null($value);
        }) + $default;
    }
}
