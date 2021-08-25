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
    public $route_id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function getRestFullRouteId()
    {
        $id = basename($this->path());

        if (is_numeric($id)) {
            return (int) $id;
        }

        return null;
    }
}
