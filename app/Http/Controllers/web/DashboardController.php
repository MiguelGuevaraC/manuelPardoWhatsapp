<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\GroupMenu;
use App\Models\WhatsappSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    
        // Establecer valores predeterminados si no se proporcionan fechas
        if (!$fechaInicio) {
            $fechaInicio = now()->startOfYear()->format('Y-m-d');
        }
        if (!$fechaFin) {
            $fechaFin = now()->format('Y-m-d');
        }
    
        // Asegurarse de que la fecha de fin sea igual o posterior a la fecha de inicio
        if (new \DateTime($fechaFin) < new \DateTime($fechaInicio)) {
            return response()->json([
                'error' => 'La fecha de fin no puede ser anterior a la fecha de inicio.'
            ], 400);
        }
    
        $costUnitario = '0.20';
    
        // Crear consulta para filtrar datos
        $query = WhatsappSend::query();
        $query->where('created_at', '>=', $fechaInicio)
              ->where('created_at', '<=', $fechaFin);
    
        // Ordenar los resultados por fecha en orden ascendente
        $mensajes = $query->orderBy('created_at', 'asc')->get();
    
        // Agrupar los datos por mes y año
        $mensajesPorMes = $mensajes->groupBy(function($date) {
            return $date->created_at->format('Y-m'); 
        });
    
        // Calcular los datos agrupados por mes y año
        $data = [
            'totalMensajes' => $mensajes->count(),
            'costoUnitario' => $costUnitario,
            'costoTotal' => $mensajes->count() * $costUnitario,
            'mensajesPorFecha' => $mensajesPorMes->map->count(),
            'costosPorFecha' => $mensajesPorMes->map(function($group) use ($costUnitario) {
                return $group->count() * $costUnitario; // Calcular el costo por mes y año
            }),
        ];
    
        return response()->json($data);
    }
    
    

}
