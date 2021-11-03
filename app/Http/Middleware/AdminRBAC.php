<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AdminRules;
use App\Models\AdminGroups;
use App\Models\AdminSyslog;
use stdClass;

class AdminRBAC
{
    const EXCEPT_ROUTE = [
        'admin/login',
    ];

    public function handle(Request $request, Closure $next)
    {
        $except_routes = config('adminrbac.except_routes', self::EXCEPT_ROUTE);

        //跳过不验证指定路由
        if ($request->is($except_routes)) {
            return $next($request);
        }

        $rule = $this->matchRules($request);

        //不存在
        if (!$rule instanceof AdminRules)
        {
            return $next($request);
        }

        //开启日志
        if ($rule->is_log == 1)
        {
            $request->m_api_behavior_rule = $rule;
        }

        //不验证权限
        if ($rule->status == 0)
        {
            return $next($request);
        }

        $admin = $request->user('admin');

        //系统管理员
        if ($admin->id == 1)
        {
            return $next($request);
        }

        //管理员被禁用
        if ($admin->status == 0)
        {
            return response()->json(['message' => 'Disabled'], 401);
        }

        //没有权限
        if (!($admin->group instanceof AdminGroups) || !$admin->group->rule()->where('admin_group_rules.rule_id', $rule->id)->count())
        {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $this->recordBehavior($request, $response);
    }

    /**
     * 解析restful风格的path 拼接成rbac需要形式
     *
     * @param string $path
     * @return string
     */
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

    /**
     * 匹配带参数路由,成功返回adminrules对象
     *
     * @param Illuminate\Http\Request $request
     * @return object
     */
    protected function matchRules($request)
    {
        $restful_path = $this->getRestfulPath($request->path());
        $rules = AdminRules::select()
            ->whereRaw('find_in_set(?,api_http_method)', [$request->method()])
            ->where('api_behavior', $restful_path)
            ->get();
        $count = $rules->count();

        if ($count == 1)
        {
            $rule = $rules[0];
        }
        elseif ($count > 1)
        {
            $rules = $rules->filter(function ($item, $key) use ($request)
            {
                $req_params_str = http_build_query($request->all());
                $db_params = explode('&', $item->params);
                $db_params_count = count($db_params);
                $true_count = 0;

                foreach ($db_params as $db_param)
                {
                    //当前请求参数是否包含路由配置参数
                    if (strpos($req_params_str, $db_param) !== false)
                    {
                        $true_count++;
                    }
                }

                //路由规则中配置的参数和当前请求匹配成功的参数相等，说明当前请求路由为配置路由
                if ($db_params_count === $true_count)
                {
                    return true;
                }
            });

            $rule = $rules->first() ?? new stdClass;
        }
        else
        {
            $rule = new stdClass;
        }

        return $rule;
    }

    /**
     * 记录日志
     *
     * @param Illuminate\Http\Request $request
     * @param Illuminate\Http\Response $response
     * @return void
     */
    protected function recordBehavior($request, $response)
    {
        if (in_array($response->getStatusCode(), [500, 401, 403, 404, 429, 422, 301, 302]))
        {
            return;
        }

        if (!$request->m_api_behavior_rule instanceof AdminRules)
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
