<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Compromiso;
use App\Models\GroupMenu;
use App\Models\MessageWhasapp;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
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

        $message = MessageWhasapp::where('responsable_id', $user->person_id)->first() ?? '';

        if (in_array($lastPart, $accesses)) {
            $groupMenu = GroupMenu::getFilteredGroupMenusSuperior($user->typeofUser_id);
            $groupMenuLeft = GroupMenu::getFilteredGroupMenus($user->typeofUser_id);

            return view('Modulos.Message.index', compact('user', 'groupMenu', 'groupMenuLeft', 'message'));
        } else {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function showExample()
    {
        $user = Auth::user();
        $message = MessageWhasapp::where('responsable_id', $user->person_id)->first() ?? (object) [
            'title' => 'titulo',
            'block1' => 'block1',
            'block2' => 'block2',
            'block3' => 'block3',
        ];

        $compromiso = Compromiso::find(5) ?? (object) [
            'cuotaNumber' => '2',
            'paymentAmount' => '1000',
            'conceptDebt' => 'Junio, Julio',
            'student_id' => null,
        ];

        $student = $compromiso->student_id ? Person::find($compromiso->student_id) : (object) [
            'names' => 'Miguel Guevara',
            'documentNumber' => '12345678',
            'grade' => '5to',
            'section' => 'A',
            'level' => 'Secundaria',
            'representativeDni' => '12345678',
            'representativeNames' => 'Jose Guevara',
        ];

        $blocks = [
            'title' => str_replace(
                ['{{numCuotas}}'],
                [$compromiso->cuotaNumber],
                $message->title
            ),
            'block1' => str_replace(
                ['{{numCuotas}}', '{{nombreApoderado}}', '{{dniApoderado}}', '{{nombreAlumno}}', '{{codigoAlumno}}', '{{grado}}', '{{seccion}}', '{{nivel}}', '{{meses}}', '{{montoPago}}'],
                [$compromiso->cuotaNumber, $student->representativeNames, $student->representativeDni, $student->names, $student->documentNumber, $student->grade, $student->section, $student->level, $compromiso->conceptDebt, $compromiso->paymentAmount],
                $message->block1
            ),
            'block2' => str_replace(
                ['{{numCuotas}}', '{{nombreApoderado}}', '{{dniApoderado}}', '{{nombreAlumno}}', '{{codigoAlumno}}', '{{grado}}', '{{seccion}}', '{{nivel}}', '{{meses}}', '{{montoPago}}'],
                [$compromiso->cuotaNumber, $student->representativeNames, $student->representativeDni, $student->names, $student->documentNumber, $student->grade, $student->section, $student->level, $compromiso->conceptDebt, $compromiso->paymentAmount],
                $message->block2
            ),
            'block3' => str_replace(
                ['{{numCuotas}}', '{{nombreApoderado}}', '{{dniApoderado}}', '{{nombreAlumno}}', '{{codigoAlumno}}', '{{grado}}', '{{seccion}}', '{{nivel}}', '{{meses}}', '{{montoPago}}'],
                [$compromiso->cuotaNumber, $student->representativeNames, $student->representativeDni, $student->names, $student->documentNumber, $student->grade, $student->section, $student->level, $compromiso->conceptDebt, $compromiso->paymentAmount],
                $message->block3
            ),
        ];

        return response()->json($blocks);
    }

    public function store(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'title' => 'nullable|string',
            'block1' => 'nullable|string',
            'block2' => 'nullable|string',
            'block3' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = Auth::user();
        $message = MessageWhasapp::where('responsable_id', $user->person_id)->first();

        // Preparar los datos para actualizar o crear
        $messageData = [
            'title' => $request->input('title', $message->title ?? 'titulo'),
            'block1' => $request->input('block1', $message->block1 ?? 'block1'),
            'block2' => $request->input('block2', $message->block2 ?? 'block2'),
            'block3' => $request->input('block3', $message->block3 ?? 'block3'),
        ];

        // Validar etiquetas permitidas
        $allowedTags = [
            '{{numCuotas}}',
            '{{nombreApoderado}}',
            '{{dniApoderado}}',
            '{{nombreAlumno}}',
            '{{codigoAlumno}}',
            '{{grado}}',
            '{{seccion}}',
            '{{nivel}}',
            '{{montoPago}}',
            '{{meses}}',
        ];

        foreach ($messageData as $key => $value) {
            if (preg_match_all('/{{(.*?)}}/', $value, $matches)) {
                foreach ($matches[1] as $tag) {
                    if (!in_array('{{' . $tag . '}}', $allowedTags)) {
                        return response()->json(['error' => 'Etiqueta no permitida: ' . $tag], 422);
                    }
                }
            }
        }

        // Actualizar o crear el mensaje
        $compromiso = MessageWhasapp::updateOrCreate(
            [
                'responsable_id' => $user->person_id,
            ],
            $messageData
        );

        return response()->json($compromiso, 200);
    }

}
