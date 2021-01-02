<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\AdminGroups;
use App\Models\AdminRules;
use App\Models\AdminSyslog;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'scope:admin'])->except('login');
    }

    public function login(Request $request, AdminRules $adminRule)
    {
        $request->validate([
            'username' => 'bail|required|string',
            'password' => 'bail|required|string',
        ]);

        $admin = Admin::select()
            ->where(function ($query) use ($request)
            {
                $query->where('mobile', $request->username)
                    ->orWhere('account', $request->username)
                    ->orWhere('email', $request->username);
            })
            ->first();

        if (!$admin || false === Hash::check($request->password, $admin->password))
        {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => ['username' => ['用户名或密码错误']]], 422);
        }

        if (1 != $admin->status)
        {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => ['username' => ['账号已冻结']]], 422);
        }

        $menu = AdminGroups::where('id', $admin->group_id)->value('cache') ?? $adminRule->toTree();
        $admin->log()->create([
            'admin_id' => $admin->id,
            'ip' => $request->getClientIp(),
            'method' => $request->method(),
            'log' => '管理员登录',
            'params' => $request->all()
        ]);
        $token = $admin->getToken();

        return response()->json(compact('token', 'menu', 'admin'));
    }

    public function logout(Request $request)
    {
        $request->user('admin')->tokens()->delete();

        return response()->json();
    }

    public function index(Request $request)
    {
        $admins = Admin::where('id', '<>', 1)
            ->with(['group' => function ($query)
            {
                $query->select('id', 'title');
            }])
            ->latest()
            ->paginate($request->input('per_page', 20));

        return $admins;
    }

    public function show(Request $request, $id)
    {
        if (1 == $id)
        {
            abort(404);
        }

        $admin = Admin::select('*')
            ->with(['group' => function ($query)
            {
                $query->select('id', 'title');
            }])
            ->findOrFail($id);

        return response()->json($admin);
    }

    public function store(AdminRequest $request)
    {
        $data = array_filter($request->all(), function ($value)
        {
            return !is_null($value);
        });
        $admin = Admin::create($data);

        return response()->json(['id' => $admin->id]);
    }

    public function update(AdminRequest $request, $id)
    {
        $data = array_filter(
            $request->only(['name', 'password', 'mobile', 'group_id', 'status']),
            function ($value)
            {
                return !is_null($value);
            }
        );
        $admin = Admin::findOrFail($id);
        $admin->update($data);

        return response()->json();
    }

    public function destroy(Request $request, $id)
    {
        if (1 == $id)
        {
            abort(404);
        }

        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json();
    }

    public function status(Request $request, $id)
    {
        if (1 == $id)
        {
            abort(404);
        }

        $admin = Admin::select('id', 'status')->findOrFail($id);
        $admin->status = abs(1 - $admin->status);
        $admin->save();

        return response()->json();
    }

    public function syslogs(Request $request)
    {
        $syslogs = AdminSyslog::select()
            ->with([
                'admin' => function ($query)
                {
                    $query->select('id', 'name', 'account');
                }
            ])
            ->when($request->user('admin')->id > 1, function ($query) use ($request)
            {
                $query->where('admin_id', $request->user('admin')->id);
            })
            ->latest()
            ->paginate($request->input('per_page', 20));

        return $syslogs;
    }
}
