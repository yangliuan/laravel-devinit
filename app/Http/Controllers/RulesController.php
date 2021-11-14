<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RulesRequest;
use App\Models\AdminRules;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index(Request $request)
    {
        $rules = AdminRules::select()
            ->with([
                'children'=>function ($query) {
                    $query->select()
                    ->with(['children'=>function ($query) {
                        $query->select();
                    }]);
                }
            ])
            ->where('pid', 0)
            ->orderBy('sort', 'asc')
            ->latest()
            ->paginate($request->input('per_age', 15));

        return $rules;
    }

    public function store(RulesRequest $request)
    {
        $rules = AdminRules::create($request->filter());

        return response()->json(['id'=>$rules->id]);
    }

    public function show(Request $request, $id)
    {
        $rules = AdminRules::findOrFail($id);

        return response()->json($rules);
    }

    public function update(RulesRequest $request, $id)
    {
        $rules = AdminRules::findOrFail($id);
        $rules->update(
            $request->filter(
                ['icon'=>''],
                'only',
                ['pid', 'name', 'icon', 'api_http_method', 'api_behavior', 'params', 'gui_type', 'gui_behavior', 'status', 'is_log', 'sort']
            )
        );

        return response()->json($rules);
    }

    public function destroy(Request $request, $id)
    {
        $rules = AdminRules::findOrFail($id);
        $rules->delete();

        return response()->json();
    }

    public function status(Request $request, $id)
    {
        $rules = AdminRules::findOrFail($id);
        $rules->status = abs(1 - $rules->status);
        $rules->save();

        return response()->json();
    }

    public function logStatus(Request $request, $id)
    {
        $rules = AdminRules::findOrFail($id);
        $rules->is_log = abs(1 - $rules->is_log);
        $rules->save();

        return response()->json();
    }

    public function selectMenus(Request $request)
    {
        $rules = AdminRules::select('id', 'pid', 'name')
            ->with([
                'children'=>function ($query) {
                    $query->select('id', 'pid', 'name')
                    ->orderBy('sort', 'asc');
                }
            ])
            ->where('pid', 0)
            ->orderBy('sort', 'asc')
            ->latest()
            ->get();

        return $rules;
    }
}
