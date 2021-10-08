<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginLock
{
    protected $cache_default;

    public function __construct()
    {
        $this->cache_default = config('cache.default');
    }

    public function handle(Request $request, Closure $next)
    {
        $key = $this->buildLoginNameKey($request);
        //清除锁定的方法，php artisan tinker 执行Cache::tags('login_lock')->flush();
        if (Cache::tags('login_lock')->get($key) > 5) {
            $first_key = array_keys($request->all())[0];
            throw ValidationException::withMessages([$first_key => ['登录次数超出上限,账号已被锁定']]);
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (!in_array($this->cache_default, ['redis','memcached'])) {
            return;
        }

        if ($response->getStatusCode() !== 422) {
            return;
        }

        if ($this->parse422Response($response) === false) {
            return;
        }

        //记录输入密码错误次数
        $key = $this->buildLoginNameKey($request);
        if (!Cache::tags('login_lock')->get($key)) {
            Cache::tags('login_lock')->put($key, 1, 86400);
        } else {
            Cache::tags('login_lock')->increment($key);
        }
    }

    protected function parse422Response($response)
    {
        $json_arr = json_decode($response->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        $keys = array_keys($json_arr['errors']);

        if (isset($json_arr['errors'][$keys[0]][0]) && strpos($json_arr['errors'][$keys[0]][0], '密码错误') !== false) {
            return true;
        }

        return false;
    }

    protected function buildLoginNameKey($request)
    {
        $login_name =['username','mobile','account','email'];
        $key = '';

        foreach ($login_name as $key => $value) {
            if ($request->input($value)) {
                $key.=$request->input($value).$request->getClientIp();
                break;
            }
        }

        return 'login_lock_'.md5($key);
    }
}
