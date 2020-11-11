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
}
