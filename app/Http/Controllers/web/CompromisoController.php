<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Imports\CompromisoImport;
use App\Models\Compromiso;
use App\Models\GroupMenu;
use App\Models\MigrationExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $length = $request->get('length', 20);
        $filters = $request->input('filters', []);

        $query = Compromiso::with(['student'])->whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
            ->where('state', 1);

        // Aplicar filtros por columna
        foreach ($request->get('columns') as $column) {
            if ($column['searchable'] == 'true' && !empty($column['search']['value'])) {
                $searchValue = trim($column['search']['value'], '()'); // Quitar paréntesis adicionales

                switch ($column['data']) {
                    case 'student.names':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where(function ($query) use ($searchValue) {
                                $query->where('names', 'like', '%' . $searchValue . '%')
                                    ->orWhere('documentNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('identityNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('fatherSurname', 'like', '%' . $searchValue . '%')
                                    ->orWhere('motherSurname', 'like', '%' . $searchValue . '%');
                            });
                        });
                        break;
                    case 'cuotaNumber':
                        $query->where('cuotaNumber', $searchValue);
                        break;
                    case 'student.level':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('level', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'student.grade':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('grade', 'like', '%' . $searchValue . '%')
                                ->orWhere('section', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'paymentAmount':
                        $searchValue1 = (float) $searchValue;

                        $query->where('paymentAmount', 'like', '%' . $searchValue . '%');

                        break;
                    case 'expirationDate':

                        $query->where('expirationDate', 'like', '%' . $searchValue . '%');
                        break;
                    case 'conceptDebt':
                        $query->where('conceptDebt', 'like', '%' . $searchValue . '%');
                        break;
                    case 'status':
                        $query->where('status', $searchValue, 'like', '%' . $searchValue . '%');
                        break;
                }
            }
        }

        $totalRecords = $query->count();

        $list = $query->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $list,
        ]);
    }

    public function allId(Request $request)
    {
        // Obtén todos los IDs de compromisos que tienen un estado específico
        $ids = Compromiso::whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
            ->where('state', 1) // Filtra por el estado que necesitas
            ->pluck('id'); // Obtén solo los IDs

        // Devuelve los IDs en formato JSON
        return response()->json($ids);
    }

    public function actualizarCarrito(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 20);
        $filters = $request->input('filters', []);
        $markedIds = $request->input('markedIds', []); // Obtener los IDs marcados desde la solicitud

        $query = Compromiso::with(['student'])
            ->whereHas('student', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->where('state', 1);

        // Aplicar filtros por columna
        foreach ($request->get('columns') as $column) {
            if ($column['searchable'] == 'true' && !empty($column['search']['value'])) {
                $searchValue = trim($column['search']['value'], '()'); // Quitar paréntesis adicionales

                switch ($column['data']) {
                    case 'student.names':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where(function ($query) use ($searchValue) {
                                $query->where('names', 'like', '%' . $searchValue . '%')
                                    ->orWhere('documentNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('identityNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('fatherSurname', 'like', '%' . $searchValue . '%')
                                    ->orWhere('motherSurname', 'like', '%' . $searchValue . '%');
                            });
                        });
                        break;
                    case 'cuotaNumber':
                        $query->where('cuotaNumber', $searchValue);
                        break;
                    case 'student.level':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('level', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'student.grade':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('grade', 'like', '%' . $searchValue . '%')
                                ->orWhere('section', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'paymentAmount':
                        $query->where('paymentAmount', 'like', '%' . $searchValue . '%');
                        break;
                    case 'expirationDate':
                        $query->where('expirationDate', 'like', '%' . $searchValue . '%');
                        break;
                    case 'conceptDebt':
                        $query->where('conceptDebt', 'like', '%' . $searchValue . '%');
                        break;
                    case 'status':
                        $query->where('status', 'like', '%' . $searchValue . '%');
                        break;
                }
            }
        }


        $query->whereIn('id', $markedIds);

     

        $totalRecords = $query->count();

        $list = $query->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $list,
        ]);
    }

    public function importExcelCominments(Request $request)
    {
        // Validar el archivo de Excel
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener el archivo Excel del request
        $excelFile = $request->file('excelFile');

        try {

            $currentTime = now();
            $filename = $currentTime->format('YmdHis') . '_' . $excelFile->getClientOriginalName();
            $path = $excelFile->storeAs('public/import/Cominment', $filename);
            $rutaImagen = Storage::url($path);

            $tipo = 'DATA';
            $resultado = DB::select('SELECT COALESCE(MAX(CAST(SUBSTRING(number, LOCATE("-", number) + 1) AS SIGNED)), 0) + 1 AS siguienteNum FROM migration_exports a WHERE SUBSTRING(number, 1, 4) = ?', [$tipo])[0]->siguienteNum;
            $siguienteNum = (int) $resultado;

            $dataMigration = [
                'number' => $tipo . "-" . str_pad($siguienteNum, 8, '0', STR_PAD_LEFT),
                'type' => 'Cominment',
                'comment' => $request->input('comment') ?? '-',
                'routeExcel' => $rutaImagen ?? '-',
                'user_id' => Auth::user()->id,
            ];

            MigrationExport::create($dataMigration);

            Excel::import(new CompromisoImport(), $excelFile, null, \Maatwebsite\Excel\Excel::XLS);

            return redirect()->back()->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            // Capturar cualquier excepción y redirigir con mensaje de error
            return redirect()->back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }
}
