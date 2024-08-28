<?php

namespace App\Http\Controllers\web;

use App\Exports\ExportExcel;
use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsappJob;
use App\Models\Compromiso;
use App\Models\GroupMenu;
use App\Models\Person;
use App\Models\WhatsappSend;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = WhatsappSend::with(['user', 'user.person', 'conminmnet', 'student'])->whereHas('student', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->whereNot('status', 'Pendiente')
            ->where('state', 1);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

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
                                    ->orWhere('motherSurname', 'like', '%' . $searchValue . '%')
                                    ->orWhere('documentNumber', 'like', '%' . $searchValue . '%')
                                    ->orWhere('identityNumber', 'like', '%' . $searchValue . '%');;
                            });
                        });
                        break;
                    case 'status':
                        $query->where('status', 'like', '%' . $searchValue . '%');
                        break;
                    case 'student.representativeDni':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where(function ($query) use ($searchValue) {
                                $query->where('representativeDni', 'like', '%' . $searchValue . '%')
                                    ->orWhere('representativeNames', 'like', '%' . $searchValue . '%');
                            });
                        });
                        break;
                    case 'student.telephone':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where(function ($query) use ($searchValue) {
                                $query->where('telephone', 'like', '%' . $searchValue . '%');
                            });
                        });
                        break;
                    case 'conminmnet.cuotaNumber':
                        $query->whereHas('conminmnet', function ($query) use ($searchValue) {
                            $query->where('cuotaNumber', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'student.level':
                        $query->whereHas('student', function ($query) use ($searchValue) {
                            $query->where('grade', 'like', '%' . $searchValue . '%')
                                ->orWhere('section', 'like', '%' . $searchValue . '%')
                                ->orWhere('level', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'conminmnet.paymentAmount':
                        $query->whereHas('conminmnet', function ($query) use ($searchValue) {
                            $query->where('paymentAmount', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'conminmnet.expirationDate':
                        $query->whereHas('conminmnet', function ($query) use ($searchValue) {
                            $query->where('expirationDate', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'conminmnet.conceptDebt':
                        $query->whereHas('conminmnet', function ($query) use ($searchValue) {
                            $query->where('conceptDebt', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'conminmnet.created_at':
                        $query->whereHas('conminmnet', function ($query) use ($searchValue) {
                            // Convertimos el searchValue a formato de fecha compatible con la base de datos
                            try {
                                $date = Carbon::createFromFormat('d-m-Y H:i:s', $searchValue);
                                $searchValue = $date->format('Y-m-d H:i:s');
                            } catch (\Exception $e) {
                                // Si el formato no es válido, no aplicamos ningún filtro
                                $searchValue = null;
                            }

                            if ($searchValue) {
                                $query->where('created_at', 'like', '%' . $searchValue . '%');
                            }
                        });
                        break;

                }
            }
        }

        $totalRecords = $query->count();

        $list = $query->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        //  dd(json_decode($list));

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {

        $compromissoSendActive = Compromiso::where('state', 1)
            ->whereHas('student', function ($query) {
                $query->where('user_id', Auth::user()->id);
                $query->where('state', 1);
            })
            ->where('stateSend', 1)->get();

        foreach ($compromissoSendActive as $compromiso) {
            $compromisoBD = Compromiso::find($compromiso->id);
            $student = Person::find($compromisoBD->student_id);

            if ($compromisoBD && $student->telephone) {
                $compromisoPaquete[] = $compromisoBD;
            }

            if (count($compromisoPaquete) >= 50) {
                SendWhatsappJob::dispatch($compromisoPaquete, Auth::user());
                $compromisoPaquete = [];
            }

        }

        if (count($compromisoPaquete) > 0) {
            SendWhatsappJob::dispatch($compromisoPaquete, Auth::user());
        }

    }

    public function excelExport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = WhatsappSend::with(['user', 'user.person', 'conminmnet', 'student'])
            ->whereHas('student', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->where('status', 'Envio Exitoso')
            ->where('state', 1);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $data = $query->orderBy('id', 'desc')->get();

        $exportData = [];

        foreach ($data as $moviment) {
            $student = '';
            if ($moviment->student->typeofDocument != 'RUC') {
                $student = $moviment->student->names . ' ' . $moviment->student->fatherSurname;
            } else {
                $student = $moviment->student->businessName ?? '';
            }
            $student = $moviment->student->documentNumber . ' | ' . $student;

            $exportData[] = [
                'CuotasVencidas' => $moviment->cuota,
                'Estudiante' => $moviment->dniStudent . ' | ' . $moviment->namesStudent,
                'Padres' => $moviment->namesParent ?? '-',
                'InfoEstudiante' => $moviment->infoStudent ?? '',
                'Telefono' => $moviment->telephone ?? '-',
                'Meses' => $moviment->conceptSend ?? '-',
                'MontoPago' => $moviment->paymentAmount ?? '-',
                'FechaEnvio' => $moviment->created_at ? $moviment->created_at->format('Y-m-d H:i:s') : '-',
                'Mensaje' => $moviment->description ?? '-',
            ];
        }

        return Excel::download(new ExportExcel($exportData, $startDate, $endDate), 'export.xlsx');
    }

    public function pdfExport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = WhatsappSend::with(['user', 'user.person', 'conminmnet', 'student'])
            ->whereHas('student', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->where('status', 'Envio Exitoso')
            ->where('state', 1);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $data = $query->orderBy('id', 'desc')->get();

        $pdf = PDF::loadView('pdf.export', ['data' => $data,
            'dateStart' => $startDate, 'dateEnd' => $endDate])
            ->setPaper('a4', 'landscape')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download('export.pdf');
    }

}
