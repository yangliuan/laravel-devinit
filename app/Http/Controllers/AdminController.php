<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\AdminSyslog;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'bail|required|string',
            'password' => 'bail|required|string',
        ]);

        $admin = Admin::select('id', 'status', 'password')
            ->where(function ($query) use ($request) {
                $query->where('mobile', $request->username)
                    ->orWhere('account', $request->username)
                    ->orWhere('email', $request->username);
            })
            ->first();

        if (!$admin || false === Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages(['username' => ['用户名或密码错误']]);
        }

        if (1 != $admin->status) {
            throw ValidationException::withMessages(['username' => ['账号已冻结']]);
        }

        $admin->log()->create([
            'admin_id' => $admin->id,
            'ip' => $request->getClientIp(),
            'log' => '管理员登录',
            'method' => $request->method(),
            'path' => $request->path(),
            'params' => $request->all()
        ]);
        $token_type = 'Bearer';
        $token = $admin->getToken();

        return response()->json(compact('token_type', 'token'));
    }

    public function logout(Request $request)
    {
        $request->user('admin')->tokens()->where('name', 'admin')->delete();

        return response()->json();
    }

    public function info(Request $request)
    {
        $admin = $request->user('admin')->makeHidden('email', 'email_verified_at');
        $menu = $admin->getRules();

        return response()->json(compact('admin', 'menu'));
    }

    public function index(AdminRequest $request)
    {
        $admins = Admin::select()
            ->with(['group' => function ($query) {
                $query->select('id', 'title')
                    ->withDefault([
                        'id'=>0,'title'=>'无分组'
                    ]);
            }])
            ->when($request->user('admin')->id > 1, function ($query) {
                //只有系统管理员自己才能查看系统管理员
                $query->where('id', '>', 1);
            })
            ->paginate($request->input('per_page', 20));

        return $admins;
    }

    public function show(Request $request, $id)
    {
        $admin = Admin::select()
            ->with(['group' => function ($query) {
                $query->select('id', 'title')
                    ->withDefault([
                        'id'=>0,'title'=>'无分组'
                    ]);
            }])
            ->findOrFail($id);

        return response()->json($admin);
    }

    public function store(AdminRequest $request)
    {
        $admin = Admin::create($request->filter());

        return response()->json(['id' => $admin->id]);
    }

    public function update(AdminRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update($request->filter([], 'only', ['name', 'account', 'password', 'mobile', 'group_id', 'status']));

        return response()->json();
    }

    public function destroy(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        if ($id > 1) {
            $admin->delete();
        }

        return response()->json();
    }

    public function status(Request $request, $id)
    {
        $admin = Admin::select('id', 'status')->findOrFail($id);
        $admin->status = abs(1 - $admin->status);

        if ($id > 1) {
            $admin->save();
        }

        return response()->json();
    }

    public function syslogs(Request $request)
    {
        $syslogs = AdminSyslog::select()
            ->with([
                'admin' => function ($query) {
                    $query->select('id', 'name', 'account');
                }
            ])
            ->when($request->user('admin')->id > 1, function ($query) use ($request) {
                //系统管理员查看所有人日志，普通管理员查看自己的日志
                $query->where('admin_id', $request->user('admin')->id);
            })
            ->when($request->input('start_at'), function ($query) use ($request) {
                $query->where('created_at', '>=', $request->input('start_at'));
            })
            ->when($request->input('end_at'), function ($query) use ($request) {
                $query->where('created_at', '<=', $request->input('end_at'));
            })
            ->latest()
            ->paginate($request->input('per_page', 20));

        return $syslogs;
    }

    public function sysInfo(Request $request, Admin $admin)
    {
        $sysinfo = [
            ['name' => 'Timezone', 'value' => get_cfg_var("date.timezone")],
            ['name' => 'Time', 'value' => date("Y-m-d H:i:s")],
            ['name' => 'IP', 'value' => $_SERVER['SERVER_ADDR']],
            ['name' => 'Host', 'value' => $_SERVER['SERVER_NAME']],
            ['name' => 'Port', 'value' => $_SERVER['SERVER_PORT']],
            ['name' => 'Uname', 'value' => php_uname()],
            ['name' => 'Web server', 'value' => $_SERVER['SERVER_SOFTWARE']],
            ['name' => 'Server protocol', 'value' => $_SERVER['SERVER_PROTOCOL']],
            ['name' => 'PHP version', 'value' => PHP_VERSION],
            ['name' => 'PHP SAPI', 'value' => php_sapi_name()],
            ['name' => 'Upload max filesize', 'value' => get_cfg_var("upload_max_filesize")],
            ['name' => 'Max execution time', 'value' => get_cfg_var("max_execution_time") . "秒 "],
            ['name' => 'Memory limit', 'value' => get_cfg_var("memory_limit")],
            ['name' => 'Laravel version', 'value' => $laravel = app()::VERSION],
            ['name' => 'App Env', 'value' => config('app.env')],
            ['name' => 'App debug', 'value' => config('app.debug')],
            ['name' => 'Broadcasting driver', 'value' => config('broadcasting.default')],
            ['name' => 'Cache driver', 'value' => config('cache.default')],
            ['name' => 'Filesystems driver', 'value' => config('filesystems.default')],
            ['name' => 'Queue driver', 'value' => config('queue.default')],
            ['name' => 'Database driver', 'value' => config('database.default')],
            ['name' => 'Database version', 'value' => $admin->databaseVersion()],
        ];

        return response()->json($sysinfo);
    }
}
