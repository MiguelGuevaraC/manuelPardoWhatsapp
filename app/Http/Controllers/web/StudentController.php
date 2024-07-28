<?php

namespace App\Http\Controllers\web;

use App\Exports\PersonExport;
use App\Http\Controllers\Controller;
use App\Imports\PersonImport;
use App\Models\GroupMenu;
use App\Models\MigrationExport;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
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

            return view('Modulos.Student.index', compact('user', 'groupMenu', 'groupMenuLeft'));
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

        $query = Person::where('user_id', Auth::user()->id)
            ->where('state', 1)
            ->orderBy('id', 'desc');

        // Aplicar filtros por columna
        foreach ($request->get('columns') as $column) {
            if ($column['searchable'] == 'true' && !empty($column['search']['value'])) {
                $searchValue = trim($column['search']['value'], '()'); // Quitar paréntesis adicionales

                switch ($column['data']) {
                    case 'documentNumber':
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('names', 'like', '%' . $searchValue . '%')
                                ->orWhere('fatherSurname', 'like', '%' . $searchValue . '%')
                                ->orWhere('motherSurname', 'like', '%' . $searchValue . '%')
                                ->orWhere('documentNumber', 'like', '%' . $searchValue . '%')
                                ->orWhere('identityNumber', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'id':
                        $query->where('id', 'like', '%' . $searchValue . '%');
                        break;
                    case 'level':
                        $query->where('level', 'like', '%' . $searchValue . '%');
                        break;
                    case 'grade':
                        $query->where('grade', 'like', '%' . $searchValue . '%');
                        break;
                    case 'section':
                        $query->where('section', 'like', '%' . $searchValue . '%');
                        break;
                    case 'representativeDni':
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('representativeNames', 'like', '%' . $searchValue . '%')
                                ->orWhere('representativeDni', 'like', '%' . $searchValue . '%');
                        });
                        break;
                    case 'telephone':
                        $query->where('telephone', 'like', '%' . $searchValue . '%');
                        break;
                }
            }
        }

        $totalRecords = $query->count();

        $list = $query->skip($start)
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
        // Validar el archivo Excel y otros campos requeridos
        $validator = validator()->make($request->all(), [

            'excelFile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $file = $request->file('excelFile');
        // $excelData = Excel::toArray([], $file);
        dd($file);

        // // Extraer y procesar datos de la primera hoja
        // $students = $excelData[0];

        // // Iterar sobre los datos y crear registros
        // foreach ($students as $index => $student) {
        //     if ($index === 0) {
        //         // Saltar la fila del encabezado
        //         continue;
        //     }

        //     $data = [
        //         'typeofDocument' => $request->input('typeofDocument'),
        //         'documentNumber' => $request->input('documentNumber'),
        //         'address' => $request->input('address') ?? null,
        //         'phone' => $request->input('phone') ?? null,
        //         'email' => $request->input('email') ?? null,
        //         'origin' => $request->input('origin') ?? null,
        //         'ocupation' => $request->input('ocupation') ?? null,
        //         'names' => null,
        //         'fatherSurname' => null,
        //         'motherSurname' => null,
        //         'businessName' => null,
        //         'representativeDni' => null,
        //         'representativeNames' => null,
        //     ];

        //     if ($request->input('typeofDocument') == 'DNI') {
        //         $data['names'] = $student[0] ?? null;
        //         $data['fatherSurname'] = $student[1] ?? null;
        //         $data['motherSurname'] = $student[2] ?? null;
        //     } elseif ($request->input('typeofDocument') == 'RUC') {
        //         $data['businessName'] = $student[3] ?? null;
        //         $data['representativeDni'] = $student[4] ?? null;
        //         $data['representativeNames'] = $student[5] ?? null;
        //     }

        //     // Crear registro en la base de datos
        //     Person::create($data);
        // }

        return response()->json(['message' => 'Estudiantes cargados exitosamente'], 200);
    }

    public function exportExcel()
    {

        return Excel::download(new PersonExport(''), 'user-list.xlsx');
    }

    public function importExcel(Request $request)
    {
        // Validar el archivo de Excel
        $request->validate([
            'excelFile' => 'required|file',
        ]);

        // Obtener el archivo Excel del request
        $excelFile = $request->file('excelFile');

        try {

            $currentTime = now();
            $filename = $currentTime->format('YmdHis') . '_' . $excelFile->getClientOriginalName();
            $path = $excelFile->storeAs('public/import/student', $filename);
            $rutaImagen = Storage::url($path);

            $tipo = 'DATA';
            $resultado = DB::select('SELECT COALESCE(MAX(CAST(SUBSTRING(number, LOCATE("-", number) + 1) AS SIGNED)), 0) + 1 AS siguienteNum FROM migration_exports a WHERE SUBSTRING(number, 1, 4) = ?', [$tipo])[0]->siguienteNum;
            $siguienteNum = (int) $resultado;

            $dataMigration = [
                'number' => $tipo . "-" . str_pad($siguienteNum, 8, '0', STR_PAD_LEFT),
                'type' => 'Estudiantes',
                'comment' => $request->input('comment') ?? '-',
                'routeExcel' => $rutaImagen ?? '-',
                'user_id' => Auth::user()->id,
            ];

            if ($excelFile) {

                $extension = $excelFile->getClientOriginalExtension();

                if ($extension === 'xls') {
                    Excel::import(new PersonImport(), $excelFile, null, \Maatwebsite\Excel\Excel::XLS);
                } elseif ($extension === 'xlsx') {
                    Excel::import(new PersonImport(), $excelFile, null, \Maatwebsite\Excel\Excel::XLSX);
                } else {
                    return redirect()->back()->with('error', 'Formato de archivo no soportado.');
                }
    
                return redirect()->back()->with('success', 'Datos importados correctamente.');
            }

 MigrationExport::create($dataMigration);


            // Redireccionar con mensaje de éxito
            return redirect()->back()->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            // Capturar cualquier excepción y redirigir con mensaje de error
            return redirect()->back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $object = Person::find($id);
        if (!$object) {
            return response()->json(
                ['message' => 'User not found'], 404
            );
        }

        $object->state = 0;
        $object->save();
    }

    public function show(int $id)
    {

        $object = Person::find($id);
        if ($object) {
            return response()->json($object, 200);
        }
        return response()->json(
            ['message' => 'Person not found'], 404
        );

    }

    public function update(Request $request, string $id)
    {
        $object = Person::find($id);

        if (!$object) {
            return response()->json(['message' => 'Person not found'], 404);
        }

        $validator = validator()->make($request->all(), [
            'documentNumber' => [
                'required',
                Rule::unique('people')->ignore($object->id)->whereNull('deleted_at'),
            ],

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $object->documentNumber = $request->input('documentNumber');

        $object->names = $request->input('names');
        $object->fatherSurname = $request->input('fatherSurname');
        $object->motherSurname = $request->input('motherSurname');
        $object->businessName = $request->input('businessName');
        $object->level = $request->input('level');
        $object->grade = $request->input('grade');
        $object->section = $request->input('section');
        $object->representativeDni = $request->input('representativeDni');
        $object->representativeNames = $request->input('representativeNames');
        $object->telephone = $request->input('telephone');

        $object->save();
        $object = Person::find($object->id);

        return response()->json($object, 200);
    }

}
