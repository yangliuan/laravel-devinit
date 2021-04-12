<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\AdminGroups;
use App\Models\AdminRules;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'scope:admin']);
    }

    public function index(Request $request)
    {
        $adminGroups = AdminGroups::select()
            ->latest()
            ->paginate($request->input('per_page', 20));

        return $adminGroups;
    }

    public function show(Request $request, $id)
    {
        $adminGroup = AdminGroups::findOrFail($id);

        return response()->json($adminGroup);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required|string|max:20|unique:admin_groups,title',
            'desc' => 'bail|nullable|string|max:100',
        ], [
            'title.unique' => '管理组名称已存在'
        ]);

        $adminGroup = AdminGroups::create([
            'title' => $request->input('title') ?? '',
            'desc' => $request->input('desc') ?? ''
        ]);

        return response()->json(['id' => $adminGroup->id]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => [
                'bail', 'required', 'string', 'max:20',
                Rule::unique('admin_groups', 'title')->ignore($id),
            ],
            'desc' => 'bail|nullable|string|max:100',
        ], [
            'title.unique' => '管理组名称已存在'
        ]);

        $admin = AdminGroups::findOrFail($id);
        $admin->update([
            'title' => $request->input('title') ?? '',
            'desc' => $request->input('desc') ?? ''
        ]);

        return response()->json();
    }

    public function destroy(Request $request, $id)
    {
        $admin = AdminGroups::findOrFail($id);
        $admin->delete();
        DB::table('admin_group_rules')->where('group_id', $id)->delete();

        return response()->json();
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

        $adminGroup = AdminGroups::findOrFail($id);
        $pids = AdminRules::whereIn('id', $request->rules)->pluck('pid')->toArray();
        $ppids = AdminRules::whereIn('id', $pids)->pluck('pid')->toArray();

        $rules = array_merge($pids, $ppids, $request->rules);
        $rules = array_unique($rules);
        $rules = array_filter($rules, function ($value)
        {
            return 0 !== $value;
        });
        $adminGroup->rule()->sync($rules);
        $adminGroup->refreshCache();

        return response()->json();
    }
}
