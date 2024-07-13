<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('ensureTokenIsValid');
    }
    public function index()
    {
        $userTypeId = auth()->user()->typeofUser_id;

        $groupMenus = GroupMenu::getFilteredGroupMenus($userTypeId);

        return response()->json($groupMenus);
    }

    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => [
                'required',
                Rule::unique('group_menus')->whereNull('deleted_at'),
            ],
            'icon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'icon' => $request->input('icon'),
        ];

        $object = GroupMenu::create($data);
        $object = GroupMenu::with(['optionMenus'])->find($object->id);
        return response()->json($object, 200);
    }

    public function show(int $id)
    {

        $object = GroupMenu::with(['optionMenus'])->find($id);
        if ($object) {
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'Group Menu not found'], 404
        );

    }

    public function update(Request $request, int $id)
    {

        $object = GroupMenu::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'Group Menu not found'], 404
            );
        }
        $validator = validator()->make($request->all(), [
            'name' => [
                'required',
                Rule::unique('group_menus')->ignore($id)->whereNull('deleted_at'),
            ],
            'icon' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'icon' => $request->input('icon'),
        ];

        $object->update($data);
        $object = GroupMenu::with(['optionMenus'])->find($object->id);
        return response()->json($object, 200);

    }

    public function destroy(int $id)
    {
        $groupMenu = GroupMenu::find($id);
        if (!$groupMenu) {
            return response()->json(
                ['message' => 'Group Menu not found'], 404
            );
        }
        if ($groupMenu->optionMenus()->count() > 0) {
            return response()->json(
                ['message' => 'Group Menu has option menus associated'], 409
            );
        }
        $groupMenu->delete();

        return response()->json(
            ['message' => 'Group Menu deleted successfully']
        );

    }
}
