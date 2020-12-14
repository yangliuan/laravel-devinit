<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public static function collection($resource)
    {
        $resource->loadMissing(self::getRequestIncludes());

        return parent::collection($resource);
    }
}
