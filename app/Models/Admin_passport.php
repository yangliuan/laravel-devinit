<?php

namespace App\Models;

use App\Traits\DateFormat;
use App\Traits\PasswordHandle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, DateFormat, PasswordHandle;

    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'account',
        'mobile',
        'email',
        'email_verified_at',
        'password',
        'group_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($admin) {
            //管理员密码加密
            static::hashBcrypt($admin);
            //保证系统管理员信息不被修改
            static::sysAdminKeep($admin);
        });
    }

    public function group()
    {
        return $this->belongsTo('App\Models\AdminGroups', 'group_id', 'id')
            ->withDefault(['id' => 0, 'title' => '', 'desc' => '', 'status' => 0]);
    }

    public function log()
    {
        return $this->hasMany('App\Models\AdminSyslog', 'admin_id', 'id');
    }

    public function getToken()
    {
        return $this->createToken('admin', ['admin', 'common'])->accessToken;
    }

    public function getRules()
    {
        if ($this->id === 1) {
            $rules = (new AdminRules())->adminTree();
        } else {
            $rules = AdminGroups::where('id', $this->group_id)->value('cache') ?? [];
        }

        return $rules;
    }

    public function databaseVersion()
    {
        if (config('database.default') === 'mysql') {
            list($res) = \DB::select('select version() as version');

            return $res->version;
        } else {
            return '';
        }
    }
}
