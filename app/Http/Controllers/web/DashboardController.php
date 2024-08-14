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
        $fechaInicio = $request->input('fechaStart', now()->startOfYear()->format('Y-m-d'));
        $fechaFin = $request->input('fechaEnd', now()->format('Y-m-d'));

        // Verificar que la fecha de fin no sea anterior a la fecha de inicio
        if ($fechaFin < $fechaInicio) {
            return response()->json(['error' => 'La fecha de fin no puede ser anterior a la fecha de inicio.'], 400);
        }

        $costoUnitario = 0.20;

        // Filtrar datos por el rango de fechas
        $mensajes = WhatsappSend::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->orderBy('created_at', 'asc')
            ->get();

        // Agrupar los mensajes por mes y año
        $mensajesPorMes = $mensajes->groupBy(fn($item) => $item->created_at->format('Y-m-d'));

        // Calcular los datos agrupados por mes y año
        $data = [
            'totalMensajes' => $mensajes->count(),
            'costoUnitario' => $costoUnitario,
            'costoTotal' => $mensajes->count() * $costoUnitario,
            'mensajesPorFecha' => $mensajesPorMes->map->count(),
            'costosPorFecha' => $mensajesPorMes->map(fn($group) => $group->count() * $costoUnitario),
        ];

        return response()->json($data);
    }

}
