<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiRequest;

class RulesRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
            {
                return [
                    // CREATE ROLES
                    'pid'=>'bail|required|integer|min:0',
                    'name'=>'bail|required|string|max:100',
                    'icon'=>'bail|nullable|string|max:100',
                    'api_http_method'=>'bail|required|string|in:GET,POST,PUT,PATCH,DELETE',
                    'api_behavior'=>'bail|required|string|max:255',
                    'params'=>'bail|nullable|string',
                    'gui_type'=>'bail|required|integer|min:0',
                    'gui_behavior'=>'bail|required|string',
                    'status'=>'bail|required|integer|in:0,1',
                    'is_log'=>'bail|required|integer|in:0,1',
                    'sort'=>'bail|required|integer|min:0',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    // UPDATE ROLES
                    'pid'=>'bail|required|integer|min:0',
                    'name'=>'bail|required|string|max:100',
                    'icon'=>'bail|nullable|string|max:100',
                    'api_http_method'=>'bail|required|string|in:GET,POST,PUT,PATCH,DELETE',
                    'api_behavior'=>'bail|required|string|max:255',
                    'params'=>'bail|nullable|string',
                    'gui_type'=>'bail|required|integer|min:0',
                    'gui_behavior'=>'bail|required|string',
                    'status'=>'bail|required|integer|in:0,1',
                    'is_log'=>'bail|required|integer|in:0,1',
                    'sort'=>'bail|required|integer|min:0',
                ];
            }
            case 'GET':
            {
                return [
                    // LIST ROLES
                    'page'=>'bail|required|integer|min:1',
                    'per_page'=>'bail|required|integer|min:1'
                ];
            }
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }
}
