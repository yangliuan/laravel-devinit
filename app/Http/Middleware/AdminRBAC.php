<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AdminRules;
use App\Models\AdminSyslog;

class AdminRBAC
{
    const EXCEPT_ROUTE = [
        'admin/login',
    ];

    public function handle(Request $request, Closure $next)
    {
        //跳过指定路由
        foreach (self::EXCEPT_ROUTE as $value)
        {
            if ($request->is($value))
            {
                return $next($request);
            }
        }

        $admin = $request->user('admin');

        if ($admin->status == 0)
        {
            return response()->json(['message' => 'Disabled'], 401);
        }

        $restfulPath = $this->getRestfulPath($request->path());

        $rules = AdminRules::select()
            ->whereRaw('find_in_set(?,api_http_method)', [$request->method()])
            ->where('api_behavior', $restfulPath)
            ->first();

        //不存在
        if (!$rules instanceof AdminRules)
        {
            return $next($request);
        }

        //开启日志
        if ($rules->is_log == 1)
        {
            $request->m_api_behavior_rules = $rules;
        }

        //系统管理员
        if ($admin->id == 1)
        {
            return $next($request);
        }

        //不验证权限
        if ($rules->status == 0)
        {
            return $next($request);
        }

        //没有权限
        if (!$admin->group()->where('admin_groups.rules_id', $rules->id)->count())
        {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }

    protected function getRestfulPath(string $path)
    {
        $pathInfo = pathinfo($path);

        if (isset($pathInfo['basename']) && !empty($pathInfo['basename']))
        {
            if (is_numeric($pathInfo['basename']))
            {
                $rulePath = $pathInfo['dirname'] . '/';
            }
            else
            {
                $rulePath = $pathInfo['dirname'] . '/' . $pathInfo['basename'];
            }
        }
        else
        {
            $rulePath = $path;
        }

        return $rulePath;
    }

    public function terminate($request, $response)
    {
        $this->recordBehavior($request, $response);
    }

    //记录日志
    protected function recordBehavior($request, $response)
    {
        if (in_array($response->getStatusCode(), [500, 401, 403, 404, 429, 422, 301, 302]))
        {
            return;
        }

        if (!$request->m_api_behavior_rules instanceof AdminRules)
        {
            return;
        }

        AdminSyslog::create([
            'admin_id' => $request->user('admin')->id,
            'log' => $request->m_api_behavior_rules->name,
            'ip' => $request->getClientIp(),
            'method' => $request->method(),
            'params' => $request->all()
        ]);
    }
}
