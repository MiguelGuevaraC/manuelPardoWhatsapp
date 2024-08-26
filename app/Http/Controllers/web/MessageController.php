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
            'block4' => 'block4',
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

        $tags = [
            '{{numCuotas}}',
            '{{nombreApoderado}}',
            '{{dniApoderado}}',
            '{{nombreAlumno}}',
            '{{codigoAlumno}}',
            '{{grado}}',
            '{{seccion}}',
            '{{nivel}}',
            '{{meses}}',
            '{{montoPago}}',
        ];

        $values = [
            $compromiso->cuotaNumber,
            $student->representativeNames,
            $student->representativeDni,
            $student->names,
            $student->documentNumber,
            $student->grade,
            $student->section,
            $student->level,
            $compromiso->conceptDebt,
            $compromiso->paymentAmount,
        ];

        $blocks = [
            'title' => str_replace($tags, $values, $message->title),
            'block1' => str_replace($tags, $values, $message->block1),
            'block2' => str_replace($tags, $values, $message->block2),
            'block3' => str_replace($tags, $values, $message->block3),
            'block4' => str_replace($tags, $values, $message->block4),
        ];

        return response()->json($blocks);
    }

    public function store(Request $request)
    {
        // Función para contar caracteres teniendo en cuenta caracteres especiales
        function countSpecialChars($text)
        {
            // Mapa de caracteres especiales y su peso en caracteres ASCII
            $specialChars = [
                'á' => 5, 'é' => 5, 'í' => 5, 'ó' => 5, 'ú' => 5,
                'ü' => 5, 'ñ' => 5,
                'Á' => 5, 'É' => 5, 'Í' => 5, 'Ó' => 5, 'Ú' => 5,
                'Ü' => 5, 'Ñ' => 5,
                '/' => 5, '\\' => 5,
                // Puedes agregar más caracteres especiales aquí
            ];

            $length = mb_strlen($text); // Largo total del texto
            $specialCount = 0;

            // Iterar sobre cada carácter del texto
            foreach (preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY) as $char) {
                if (isset($specialChars[$char])) {
                    $specialCount += $specialChars[$char] - 1; // Restar 1 porque ya cuenta como 1
                }
            }

            return $length + $specialCount;
        }

        // Validar la longitud considerando caracteres especiales
        $validator = validator()->make($request->all(), [
            'title' => ['required', 'string', function ($attribute, $value, $fail) {
                if (countSpecialChars($value) > 300) {
                    $fail('El título no debe exceder los 300 caracteres.');
                }
            }],
            'block1' => ['required', 'string', function ($attribute, $value, $fail) {
                if (countSpecialChars($value) > 300) {
                    $fail('El párrafo 1 no debe exceder los 300 caracteres.');
                }
            }],
            'block2' => ['required', 'string', function ($attribute, $value, $fail) {
                if (countSpecialChars($value) > 300) {
                    $fail('El párrafo 2 no debe exceder los 300 caracteres.');
                }
            }],
            'block3' => ['required', 'string', function ($attribute, $value, $fail) {
                if (countSpecialChars($value) > 300) {
                    $fail('El párrafo 3 no debe exceder los 300 caracteres.');
                }
            }],
            'block4' => ['required', 'string', function ($attribute, $value, $fail) {
                if (countSpecialChars($value) > 300) {
                    $fail('El párrafo 4 no debe exceder los 300 caracteres.');
                }
            }],
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'block1.required' => 'El párrafo 1 es obligatorio.',
            'block1.string' => 'El párrafo 1 debe ser una cadena de texto.',
            'block2.required' => 'El párrafo 2 es obligatorio.',
            'block2.string' => 'El párrafo 2 debe ser una cadena de texto.',
            'block3.required' => 'El párrafo 3 es obligatorio.',
            'block3.string' => 'El párrafo 3 debe ser una cadena de texto.',
            'block4.required' => 'El párrafo 4 es obligatorio.',
            'block4.string' => 'El párrafo 4 debe ser una cadena de texto.',
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
            'block4' => $request->input('block4', $message->block4 ?? 'block4'),
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
