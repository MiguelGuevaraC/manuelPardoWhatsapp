<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use App\Models\MigrationExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MigrationController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $typeUser = $user->typeUser;

        $accesses = $typeUser->getAccess($typeUser->id);

        $currentRoute = $request->path();
        $currentRouteParts = explode('/', $currentRoute);
        $lastPart = end($currentRouteParts);

        if (in_array($lastPart, $accesses)) {
            $groupMenu = GroupMenu::getFilteredGroupMenusSuperior($user->typeofUser_id);
            $groupMenuLeft = GroupMenu::getFilteredGroupMenus($user->typeofUser_id);

            return view('Modulos.Migration.index', compact('user', 'groupMenu', 'groupMenuLeft'));
        } else {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function all(Request $request)
    {
     
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 15); 
    
        $list = MigrationExport::where('user_id', Auth::user()->id)
            ->where('state', 1)
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();
    
        $totalRecords = MigrationExport::where('user_id', Auth::user()->id)
            ->where('state', 1)
            ->count();
    
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $list,
        ]);
    }
    

    // public function store(Request $request)
    // {
    //     $validator = validator()->make($request->all(), [
    //         'name' => [
    //             'required',
    //             Rule::unique('group_menus')->whereNull('deleted_at'),
    //         ],
    //         'icon' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first()], 422);
    //     }

    //     $data = [
    //         'name' => $request->input('name'),
    //         'icon' => $request->input('icon'),
    //     ];

    //     $object = MigrationExport::create($data);
    //     $object = MigrationExport::with(['user'])->find($object->id);
    //     return response()->json($object, 200);
    // }

    // public function show(int $id)
    // {

    //     $object = MigrationExport::with(['user'])->find($id);
    //     if ($object) {
    //         return response()->json($object, 200);
    //     }
    //     return response()->json(
    //         ['message' => 'Group Menu not found'], 404
    //     );

    // }

    // public function update(Request $request, int $id)
    // {

    //     $object = MigrationExport::find($id);
    //     if (!$object) {
    //         return response()->json(
    //             ['message' => 'Group Menu not found'], 404
    //         );
    //     }
    //     $validator = validator()->make($request->all(), [
    //         'name' => [
    //             'required',
    //             Rule::unique('group_menus')->ignore($id)->whereNull('deleted_at'),
    //         ],
    //         'icon' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first()], 422);
    //     }

    //     $data = [
    //         'name' => $request->input('name'),
    //         'icon' => $request->input('icon'),
    //     ];

    //     $object->update($data);
    //     $object = MigrationExport::with(['user'])->find($object->id);
    //     return response()->json($object, 200);

    // }

    // public function destroy(int $id)
    // {
    //     $groupMenu = MigrationExport::find($id);
    //     if (!$groupMenu) {
    //         return response()->json(
    //             ['message' => 'Group Menu not found'], 404
    //         );
    //     }
    //     if ($groupMenu->optionMenus()->count() > 0) {
    //         return response()->json(
    //             ['message' => 'Group Menu has option menus associated'], 409
    //         );
    //     }
    //     $groupMenu->delete();

    //     return response()->json(
    //         ['message' => 'Group Menu deleted successfully']
    //     );

    // }
}
