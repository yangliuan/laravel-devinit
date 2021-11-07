<?php
/**
 * 用户密码处理
 */
namespace App\Traits;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

trait PasswordHandle
{
    /**
     * 使用hash bcrypt加密用户面
     *
     * @param Authenticatable $authModel 用户认证模型
     * @return Illuminate\Foundation\Auth\User
     */
    public static function hashBcrypt(Authenticatable & $auth_model)
    {
        //模型保存时没有修改密码属性时 退出
        if (!$auth_model->isDirty('password')) {
            return false;
        }

        //hash值是否需要刷新
        if (Hash::needsRehash($auth_model->password)) {
            $auth_model->password = \bcrypt($auth_model->password);
            return true;
        }

        return false;
    }

    /**
     * 系统管理员信息保护 防止禁用和名称被修改
     * @param Authenticatable $authModel 用户认证模型
     * @return Illuminate\Foundation\Auth\User
     */
    public static function sysAdminKeep(Authenticatable & $auth_model)
    {
        if ($auth_model->id !== 1) {
            return false;
        }

        $auth_model->name = '系统管理员';
        $auth_model->group_id = 0;
        $auth_model->status = 1;

        return true;
    }
}
