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
        $totalCompromisosList = Compromiso::whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
            $query->where('state', 1);
        })->where('state', 1);

        $totalCompromisos = $totalCompromisosList->count();
        $amount = $totalCompromisosList->sum('paymentAmount');

        $AmountotalCompromisos = 'S/ ' . number_format($amount, 2, '.', ',');

        if (in_array($lastPart, $accesses)) {
            $groupMenu = GroupMenu::getFilteredGroupMenusSuperior($user->typeofUser_id);
            $groupMenuLeft = GroupMenu::getFilteredGroupMenus($user->typeofUser_id);

            return view('Modulos.Compromiso.index', compact('user', 'groupMenu', 'groupMenuLeft', 'AmountotalCompromisos', 'totalCompromisos'));
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
            $query->where('state', 1);
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
                                    ->orWhere('identityNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('fatherSurname', 'like', '%' . $searchValue . '%')
                                    ->orWhere('motherSurname', 'like', '%' . $searchValue . '%')
                                    ->orWhere('documentNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('identityNumber', 'like', '%' . $searchValue . '%');
                            });
                        });
                        break;
                    // case 'countCominments':
                    //     $query->whereHas('student', function ($query) use ($searchValue) {
                    //         $query->whereHas('cominments', function ($query) use ($searchValue) {
                    //             $query->havingRaw('COUNT(*) = ?', [$searchValue]);
                    //         });
                    //     });
                    //     break;
                    case 'cuotaNumber':
                        $query->where('cuotaNumber', '=', $searchValue);
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
                    case 'student.telephone':

                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('telephone', 'like', '%' . $searchValue . '%');
                        });
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
        $totalAmount = $query->sum('paymentAmount');

        $list = $query->orderBy('id', 'desc')

            ->get();

        $stateSendFilter = null;
        foreach ($request->get('columns') as $column) {
            if ($column['data'] === 'stateSend' && !empty($column['search']['value'])) {
                $stateSendFilter = $column['search']['value'];

            } else {
                // Compromiso::where('stateSend', 1)->update(['stateSend' => 0]);
            }
        }

        // Actualizar registros si el filtro stateSend es true o false
        if ($stateSendFilter !== null) {

            if ($stateSendFilter != 'null') {

                $stateSendValue = ($stateSendFilter === 'true') ? 1 : 0;

                $filteredIds = $list->pluck('id')->toArray();
                Compromiso::whereIn('id', $filteredIds)

                    ->update(['stateSend' => $stateSendValue]);

                Compromiso::whereNotIn('id', $filteredIds)->update(['stateSend' => 0]);
            }

        }
        $list = $query->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $list->transform(function ($item) {
            $item->countCominments = $item->student->cominmentsCount();
            return $item;
        });

        $compromisosSelectedQuery = Compromiso::whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->where('stateSend', 1);

        $compromisosSelected = $compromisosSelectedQuery->count();
        $compromisosAmountSelected = $compromisosSelectedQuery->sum('paymentAmount');

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'recordsSelected' => $compromisosSelected,

            'amountFiltered' => 'S/ ' . number_format($totalAmount, 2, '.', ','),
            'amountSelected' => 'S/ ' . number_format($compromisosAmountSelected, 2, '.', ','),
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
                $query->where('state', 1);
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

                                    ->orWhere('identityNumber', 'like', '%' . $searchValue . '%')
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
                    case 'conceptDebt':
                        $query->where('conceptDebt', 'like', '%' . $searchValue . '%');
                        break;
                    case 'student.telephone':

                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('telephone', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'status':
                        $query->where('status', 'like', '%' . $searchValue . '%');
                        break;
                }
            }
        }

        $query->where('stateSend', 1);

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
                'type' => 'Compromiso',
                'comment' => $request->input('comment') ?? '-',
                'routeExcel' => $rutaImagen ?? '-',
                'user_id' => Auth::user()->id,
            ];

            if ($excelFile) {

                $extension = $excelFile->getClientOriginalExtension();
                Compromiso::whereHas('student', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                    $query->where('state', 1);
                })->update(['state' => 0]);


                if ($extension === 'xls') {
                    Excel::import(new CompromisoImport(), $excelFile, null, \Maatwebsite\Excel\Excel::XLS);
                } elseif ($extension === 'xlsx') {
                    Excel::import(new CompromisoImport(), $excelFile, null, \Maatwebsite\Excel\Excel::XLSX);
                } else {
                    return redirect()->back()->with('error', 'Formato de archivo no soportado.');
                }
                MigrationExport::create($dataMigration);
                return redirect()->back()->with('success', 'Datos importados correctamente.');
            }

            return redirect()->back()->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            // Capturar cualquier excepción y redirigir con mensaje de error
            return redirect()->back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }

    public function stateSend($id)
    {
        $compromiso = Compromiso::find($id);

        if (!$compromiso) {
            return response()->json(['error' => 'Compromiso no encontrado'], 404);
        }

        $compromiso->stateSend = !$compromiso->stateSend;
        $compromiso->save();

        return response()->json(['success' => 'Estado actualizado'], 200);
    }

    public function stateSendAll($state)
    {

        $compromisos = Compromiso::get();

        foreach ($compromisos as $compromiso) {
            $compromiso = Compromiso::find($compromiso->id);
            $compromiso->stateSend = $state == "true" ? 1 : 0;
            $compromiso->save();
        }

        return response()->json(['success' => 'Estado actualizado'], 200);
    }
}
