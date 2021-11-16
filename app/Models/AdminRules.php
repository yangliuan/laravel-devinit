<?php

namespace App\Models;

class AdminRules extends BaseModel
{
    protected $table = 'admin_rules';

    protected $fillable = [
        'pid',
        'name',
        'icon',
        'api_http_method',
        'api_behavior',
        'params',
        'gui_type',
        'gui_behavior',
        'status',
        'is_log',
        'sort',
    ];

    protected $dates = [];

    protected $casts = [];

    protected $appends = [];

    protected $parentColumn = 'pid';

    public function group()
    {
        return $this->belongsToMany('App\Models\AdminGroups', 'admin_group_rules', 'rule_id', 'group_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'pid', 'id');
    }

    public function toTree(array $rules = [], $parentId = 0, $select_field = ['id','pid','name','icon','gui_type','gui_behavior'])
    {
        $branch = [];
        $admin_id = auth('admin')->user()->id;

        if (empty($rules) && $admin_id > 1) {
            $rules = static::where('status', 1)->get($select_field)->toArray();
        } elseif (empty($rules) && $admin_id === 1) {
            $rules = static::all($select_field)->toArray();
        }

        foreach ($rules as $rule) {
            if ($rule[$this->parentColumn] == $parentId) {
                $children = $this->toTree($rules, $rule[$this->getKeyName()]);

                if ($children) {
                    $rule['children'] = $children;
                }

                $branch[] = $rule;
            }
        }

        return collect($branch)->sortBy('sort')->values()->all();
    }
}
