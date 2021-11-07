<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\AdminGroups;
use App\Models\AdminRules;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $admin_groups = AdminGroups::select()
            ->latest()
            ->paginate($request->input('per_page', 20));

        return $admin_groups;
    }

    public function show(Request $request, $id)
    {
        $admin_groups = AdminGroups::findOrFail($id);

        return response()->json($admin_groups);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required|string|max:20|unique:admin_groups,title',
            'desc' => 'bail|nullable|string|max:255',
            'status'=>'bail|nullable|integer|in:1,0'
        ], [
            'title.unique' => '管理组名称已存在'
        ]);

        $admin_groups = AdminGroups::create([
            'title' => $request->input('title'),
            'desc' => $request->input('desc') ?? ''
        ]);

        return response()->json(['id' => $admin_groups->id]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => [
                'bail', 'required', 'string', 'max:20',
                Rule::unique('admin_groups', 'title')->ignore($id),
            ],
            'desc' => 'bail|nullable|string|max:255',
            'status'=>'bail|nullable|integer|in:1,0'
        ], [
            'title.unique' => '管理组名称已存在'
        ]);

        $admin_groups = AdminGroups::findOrFail($id);
        $admin_groups->update([
            'title' => $request->input('title'),
            'desc' => $request->input('desc') ?? ''
        ]);

        return response()->json();
    }

    public function destroy(Request $request, $id)
    {
        $admin_groups = AdminGroups::findOrFail($id);

        if ($admin_groups->admin()->count()) {
            throw ValidationException::withMessages(['id' => ['管理组下有管理员不能删除']]);
        }

        $admin_groups->delete();
        DB::table('admin_group_rules')->where('group_id', $id)->delete();

        return response()->json();
    }

    public function status(Request $request, $id)
    {
        $admin_groups = AdminGroups::findOrFail($id);
        $admin_groups->status = abs(1-$admin_groups->status);
        $admin_groups->save();

        return response()->json();
    }

    public function selectMenus(Request $request)
    {
        $request->validate([
            'title'=>'bail|nullable|string'
        ]);

        $admin_groups = AdminGroups::select('id', 'title')
            ->when($request->input('title'), function ($query) use ($request) {
                $query->where('title', 'like', "{$request->input('title')}%");
            })
            ->latest()
            ->get();

        return $admin_groups;
    }

    public function rules(Request $request, AdminRules $adminRule)
    {
        return response()->json($adminRule->toTree());
    }

    public function setting(Request $request, $id)
    {
        return response()->json(DB::table('admin_group_rules')->where('group_id', $id)->pluck('rule_id'));
    }

    public function set(Request $request, $id)
    {
        $request->validate([
            'rules' => 'bail|required|array',
            'rules.*' => 'bail|required_with:rules|integer|exists:admin_rules,id',
        ]);

        $admin_groups = AdminGroups::findOrFail($id);
        $pids = AdminRules::whereIn('id', $request->rules)->pluck('pid')->toArray();
        $ppids = AdminRules::whereIn('id', $pids)->pluck('pid')->toArray();

        $rules = array_merge($pids, $ppids, $request->rules);
        $rules = array_unique($rules);
        $rules = array_filter($rules, function ($value) {
            return 0 !== $value;
        });
        $admin_groups->rule()->sync($rules);
        $admin_groups->refreshCache();

        return response()->json();
    }
}
