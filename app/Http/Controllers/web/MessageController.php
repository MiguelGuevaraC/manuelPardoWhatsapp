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
    
        $compromiso = Compromiso::find(1000) ?? (object) [
            'cuotaNumber' => '2',
            'paymentAmount' => '1000',
            'conceptDebt' => 'Junio, Julio',
            'student_id' => null,
        ];
    
        $student = $compromiso->student_id ? Person::find($compromiso->student_id) : (object) [
            'names' => 'Miguel Guevara',
            'documentNumber' => '01234567890',
            'grade' => '5to',
            'section' => 'A',
            'level' => 'Secundaria',
            'representativeDni' => '12345678',
            'representativeNames' => 'Jose Guevara',
        ];
    
        $tags = ['{{numCuotas}}', '{{nombreApoderado}}', '{{dniApoderado}}', '{{nombreAlumno}}', '{{codigoAlumno}}', '{{grado}}', '{{seccion}}', '{{nivel}}', '{{meses}}', '{{montoPago}}'];
        $values = [$compromiso->cuotaNumber, $student->representativeNames, $student->representativeDni, $student->names, $student->documentNumber, $student->grade, $student->section, $student->level, $compromiso->conceptDebt, $compromiso->paymentAmount];
    
        $blocks = [
            'title' => str_replace($tags, $values, $message->title),
            'block1' => str_replace($tags, $values, $message->block1),
            'block2' => str_replace($tags, $values, $message->block2),
            'block3' => str_replace($tags, $values, $message->block3),
        ];
    
        return response()->json($blocks);
    }
    

    public function store(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'title' => 'required|string|max:1024',
            'block1' => 'required|string|max:1024',
            'block2' => 'required|string|max:1024',
            'block3' => 'required|string|max:1024',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no debe exceder los 1024 caracteres.',
            'block1.required' => 'El párrafo 1 es obligatorio.',
            'block1.string' => 'El párrafo 1 debe ser una cadena de texto.',
            'block1.max' => 'El párrafo 1 no debe exceder los 1024 caracteres.',
            'block2.required' => 'El párrafo 2 es obligatorio.',
            'block2.string' => 'El párrafo 2 debe ser una cadena de texto.',
            'block2.max' => 'El párrafo 2 no debe exceder los 1024 caracteres.',
            'block3.required' => 'El párrafo 3 es obligatorio.',
            'block3.string' => 'El párrafo 3 debe ser una cadena de texto.',
            'block3.max' => 'El párrafo 3 no debe exceder los 1024 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
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
