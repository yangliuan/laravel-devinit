<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    public $route_id;

    public function __construct()
    {
        parent::__construct();

        $this->route_id = $this->getRestFullRouteId();
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

    private function getRestFullRouteId()
    {
        $id = basename($this->path());

        if (is_numeric($id))
        {
            return (int) $id;
        }
        else
        {
            return null;
        }
    }
}
