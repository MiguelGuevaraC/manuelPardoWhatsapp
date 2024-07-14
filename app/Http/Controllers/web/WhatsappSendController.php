<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Compromiso;
use App\Models\GroupMenu;
use App\Models\Person;
use App\Models\WhatsappSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WhatsappSendController extends Controller
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

            return view('Modulos.Mensajeria.index', compact('user', 'groupMenu', 'groupMenuLeft'));
        } else {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function all(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 15);
        $filters = $request->input('filters', []);

        $query = WhatsappSend::with(['user', 'conminmnet', 'student'])->whereHas('student', function ($query) {
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

    public function store(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'arrayCompromisos' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $arrayCompromisos = $request->input('arrayCompromisos');

        foreach ($arrayCompromisos as $compromiso) {

            $compromisoBD = Compromiso::find($compromiso['id']);
            $student = Person::find($compromisoBD->student_id);

            $tipo = 'ENVI';
            $resultado = DB::select('SELECT COALESCE(MAX(CAST(SUBSTRING(number, LOCATE("-", number) + 1) AS SIGNED)), 0) + 1 AS siguienteNum FROM migration_exports a WHERE SUBSTRING(number, 1, 4) = ?', [$tipo])[0]->siguienteNum;
            $siguienteNum = (int) $resultado;

            $data = [
                'number' => $tipo . "-" . str_pad($siguienteNum, 8, '0', STR_PAD_LEFT),
                'userResponsability' => $user->person->names,
                'namesStudent' => $student->names . ' ' . $student->fatherSurname,
                'dniStudent' => $student->documentNumber,
                'namesParent' => $student->representativeDni . ' | ' . $student->representativeNames,
                'infoStudent' => $student->level . ' ' . $student->grade . ' ' . $student->section,
                'telephone' => $student->telephone,
                'description' => $compromisoBD->conceptDebt,
                'conceptSend' => $compromisoBD->conceptSend,
                'paymentAmount' => $compromisoBD->paymentAmount,
                'expirationDate' => $compromisoBD->expirationDate,
                'cuota' => $compromisoBD->cuotaNumber,

                'student_id' => $student->id,
                'user_id' => $user->id,
                'comminment_id' => $compromisoBD->comminment_id,

            ];
            WhatsappSend::create($data);
        }

    }
}
