<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\AdminGroups;
use App\Models\AdminRules;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
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
        $admin = auth('admin')->user();
        DB::table('oauth_access_tokens')->where('user_id', $admin->id)->where('name', 'admin')->delete();

        return response()->json();
    }

    public function index(Request $request)
    {
        $data = Admin::where('id', '<>', 1)
            ->with(['group' => function ($query)
            {
                $query->select('id', 'title');
            }])
            ->latest()
            ->paginate($request->input('per_page', 20));

        return new AdminResource($data);
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

        return new AdminResource($admin);
    }

    public function store(AdminRequest $request)
    {
        $data = array_filter($request->all(), function ($value)
        {
            return !is_null($value);
        });

        return new AdminResource(Admin::create($data));
    }

    public function update(AdminRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $data = array_filter($request->only(['name', 'password', 'mobile', 'group_id', 'status']), function ($value)
        {
            return !is_null($value);
        });
        $admin->update($data);

        return new AdminResource($admin);
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

        return response()->json(['id' => $admin->id, 'status' => $admin->status]);
    }
}
