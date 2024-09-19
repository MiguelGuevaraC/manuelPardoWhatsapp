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
        // Función para contar caracteres teniendo en cuenta caracteres especiales y etiquetas
        function countSpecialChars($text)
        {
            // Mapa de caracteres especiales y su peso en caracteres ASCII
            $specialChars = [
                'á' => 5, 'é' => 5, 'í' => 5, 'ó' => 5, 'ú' => 5,
                'ü' => 5, 'ñ' => 5,
                'Á' => 5, 'É' => 5, 'Í' => 5, 'Ó' => 5, 'Ú' => 5,
                'Ü' => 5, 'Ñ' => 5,
                '/' => 3, '\\' => 3,
            ];
    
            // Mapa de etiquetas y su peso en caracteres
            $tagsCount = [
                '{{numCuotas}}' => 2,
                '{{dniApoderado}}' => 8,
                '{{codigoAlumno}}' => 8,
                '{{grado}}' => 13,
                '{{seccion}}' => 1,
                '{{nivel}}' => 10,
                '{{montoPago}}' => 5,
                '{{meses}}' => 50,
                '{{nombreApoderado}}' => 40,
                '{{nombreAlumno}}' => 40,
            ];
    
            $length = mb_strlen($text); // Largo total del texto
            $specialCount = 0;
    
            // Contar caracteres especiales
            foreach (preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY) as $char) {
                if (isset($specialChars[$char])) {
                    $specialCount += $specialChars[$char] - 1; // Restar 1 porque ya cuenta como 1
                }
            }
    
            // Contar etiquetas y sumar caracteres según corresponda
            foreach ($tagsCount as $tag => $count) {
                $occurrences = substr_count($text, $tag);
                $specialCount += $occurrences * ($count - mb_strlen($tag)); // Restar longitud real de la etiqueta
            }
    
            return $length + $specialCount;
        }
    
        // Longitudes de cada párrafo
        $lengthParagraph1 = countSpecialChars($request->input('block1', ''));
        $lengthParagraph2 = countSpecialChars($request->input('block2', ''));
        $lengthParagraph3 = countSpecialChars($request->input('block3', ''));
        $lengthParagraph4 = countSpecialChars($request->input('block4', ''));

        $longestTitle = countSpecialChars($request->input('title', ''));
        if($longestTitle > 65){
            $excess = $longestTitle - 65;
            return response()->json([
                'error' => "La longitud del título es de $longestTitle caracteres, excede el límite de 65 caracteres por $excess.",
            ], 422);
        }
        
    
        // Sumar las longitudes de los cuatro párrafos
        $totalLength = $lengthParagraph1 + $lengthParagraph2 + $lengthParagraph3 + $lengthParagraph4;
    
        if ($totalLength > 900) {
            $excess = $totalLength - 900;
    
            // Identificar cuál párrafo reducir
            $longestParagraph = max($lengthParagraph1, $lengthParagraph2, $lengthParagraph3, $lengthParagraph4);
            $longestParagraphName = '';
            if ($longestParagraph == $lengthParagraph1) $longestParagraphName = 'párrafo 1';
            if ($longestParagraph == $lengthParagraph2) $longestParagraphName = 'párrafo 2';
            if ($longestParagraph == $lengthParagraph3) $longestParagraphName = 'párrafo 3';
            if ($longestParagraph == $lengthParagraph4) $longestParagraphName = 'párrafo 4';
    
            return response()->json([
                'error' => 'La suma de los 4 párrafos es: '.$totalLength.' caracteres, excede los 900 caracteres por ' . $excess . ' caracteres. Considera reducir el ' . $longestParagraphName . '.',
                'totalLength' => $totalLength,
                'excess' => $excess,
                'lengthParagraph1' => $lengthParagraph1,
                'lengthParagraph2' => $lengthParagraph2,
                'lengthParagraph3' => $lengthParagraph3,
                'lengthParagraph4' => $lengthParagraph4,
                'longestParagraph' => $longestParagraphName,
            ], 422);
        }
    
        // Validaciones adicionales y lógica de negocio aquí...
        
        // Preparar los datos para actualizar o crear
        $user = Auth::user();
        $message = MessageWhasapp::where('responsable_id', $user->person_id)->first();
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
