<?php

namespace App\Models;

class AdminSyslog extends BaseModel
{
    protected $table = 'admin_syslogs';

    protected $fillable = [
        'admin_id',
        'log',
        'ip',
        'method',
        'path',
        'params',
    ];

    protected $dates = [];

    protected $casts = [
        'params' => 'array'
    ];

    protected $appends = [];

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin', 'admin_id', 'id');
    }

    public function setParamsAttribute($value)
    {
        if (is_array($value) && isset($value['password'])) {
            $value['password'] = \preg_replace('/./', '*', $value['password']);
        }

        $this->attributes['params'] = \json_encode($value);
    }
}
