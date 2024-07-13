<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Compromiso;
use App\Models\GroupMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompromisoController extends Controller
{
    public function __construct()
    {
        $this->middleware('ensureTokenIsValid');
    }
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

            return view('Modulos.Compromiso.index', compact('user', 'groupMenu', 'groupMenuLeft'));
        } else {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function all(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 15);

        // Filtrar compromisos por user_id del usuario logueado y estado activo
        $list = Compromiso::whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
            ->where('state', 1)
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->with('student') // Cargar la relación student (estudiante)
            ->get();
            

        // Contar el total de registros filtrados
        $totalRecords = Compromiso::whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
            ->where('state', 1)
            ->count();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $list,
        ]);
    }
}
