<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use App\Models\WhatsappSend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('ensureTokenIsValid');
    }

    public function index()
    {
        $user = Auth::user();

        $groupMenu = GroupMenu::getFilteredGroupMenusSuperior($user->typeofUser_id);
        $groupMenuLeft = GroupMenu::getFilteredGroupMenus($user->typeofUser_id);

        return view('Modulos.Dashboard.index', compact('user', 'groupMenu', 'groupMenuLeft'));
    }

    public function dataDashboard(Request $request)
    {
        $fechaInicio = $request->input('fechaStart');
        $fechaFin = $request->input('fechaEnd');
        
        // Si no se proporcionan fechas, usa el primer día del mes actual hasta la fecha actual
        if (!$fechaInicio) {
            $fechaInicio = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$fechaFin) {
            $fechaFin = now()->format('Y-m-d');
        }
    
        $costUnitario = '0.20';
    
        $query = WhatsappSend::query();
    
        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    
        $mensajes = $query->get();
    
        $data = [
            'totalMensajes' => $mensajes->count(),
            'costoUnitario' =>$costUnitario,
            'costoTotal' => $mensajes->count()*$costUnitario,
            'mensajesPorFecha' => $mensajes->groupBy(function($date) {
                return $date->created_at->format('Y-m-d'); // Group by date
            })->map->count(),
            'costosPorFecha' => $mensajes->groupBy(function($date) {
                return $date->created_at->format('Y-m-d'); // Group by date
            })->map->sum('paymentAmount'),
        ];
    
        return response()->json($data);
    }
    

}
