<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
       
        $groupMenu = GroupMenu::getFilteredGroupMenusSuperior($user->typeofUser_id);
        $groupMenuLeft = GroupMenu::getFilteredGroupMenus($user->typeofUser_id);

        return view('Modulos.inicio.index', compact('user','groupMenu','groupMenuLeft'));
    }
}
