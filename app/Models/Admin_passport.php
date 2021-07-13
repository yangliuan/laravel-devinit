<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, DateFormat;

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
        $request = request();
        static::saving(
            function ($admin) use ($request) {
                //更新管理员密码
                if (
                    $request->is('admin/admin', 'admin/admin/*') &&
                    $admin->isDirty('password') &&
                    Hash::needsRehash($admin->password)
                ) {
                    $admin->password = \bcrypt($admin->password);
                }

                //保证系统管理员不被修改
                if ($admin->id === 1) {
                    $admin->name = '系统管理员';
                    $admin->group_id = 0;
                    $admin->status = 1;
                }
            }
        );
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
            $rules = (new AdminRules())->toTree();
        } else {
            $rules = AdminGroups::where('id', $this->group_id)->value('cache') ?? [];
        }

        return $rules;
    }
}
