<?php

namespace App\Imports;

use App\Models\Person;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PersonImport implements ToModel, WithHeadingRow
{
    private $headerMap = [];

    public function headingRow(): int
    {
        return 0; // Indica que la primera fila es la fila de encabezado
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        try {
            // Definir los encabezados esperados
            $expectedColumns = [
                'codigoalumno' => 'codigo_alumno',
                'nrodocdeidentidad' => 'numberIdenty',
                'docdeidentidad' => 'doc_identidad',
                'apellidopaterno' => 'apellido_paterno',
                'apellidomaterno' => 'apellido_materno',
                'nombres' => 'nombres',
                'nivel' => 'nivel',
                'grado' => 'grado',
                'seccion' => 'seccion',
                'nrodocumentoresponsabledepa' => 'nro_doc_responsable',
                'nombreresponsabledepago' => 'nombre_responsable_pago',
                'apellidomaternoresponsabled' => 'apellido_materno_responsable',
                'celularresponsabledepago' => 'telefono',
            ];

            // Si el mapeo de encabezados está vacío, significa que estamos en la fila de encabezado
            if (empty($this->headerMap)) {
                foreach ($row as $key => $value) {
                    if ($value != null) {
                        $normalizedKey = strtolower(str_replace(' ', '', $value));
                        if (array_key_exists($normalizedKey, $expectedColumns)) {
                            $this->headerMap[$expectedColumns[$normalizedKey]] = $key;
                        }
                    }
                }
                return null; // Retornar null porque esta fila no contiene datos de estudiantes
            }

            // Crear un array con los datos normalizados
            $normalizedRow = [];
            foreach ($this->headerMap as $columnName => $key) {
                $normalizedRow[$columnName] = isset($row[$key]) ? $row[$key] : null;
            }

            // Combinar "nombre_responsable_pago" y "apellido_materno_responsable"
            $normalizedRow['nombre_apellido_responsable'] = trim($normalizedRow['nombre_responsable_pago'] . ' ' . $normalizedRow['apellido_materno_responsable']);

            // Verificar que las columnas no sean nulas
            foreach ($expectedColumns as $columnName) {
                if (is_null($normalizedRow[$columnName]) && $columnName != 'apellido_materno_responsable') {
                    throw new Exception('Null value found in required columns.');
                }
            }

            // Obtener el usuario autenticado y los estudiantes actuales
            $user = Auth::user();
            $currentStudents = $user->students;

            // Almacenar números de documentos importados
            static $importedDocumentNumbers = [];
            $importedDocumentNumbers[] = $normalizedRow['codigo_alumno'];

            // Crear o actualizar la persona
            $person = Person::updateOrCreate(
                ['documentNumber' => $normalizedRow['codigo_alumno']],
                [
                    'typeofDocument' => $normalizedRow['doc_identidad'],
                    'identityNumber' => $normalizedRow['numberIdenty'],
                    'names' => $normalizedRow['nombres'],
                    'fatherSurname' => $normalizedRow['apellido_paterno'],
                    'motherSurname' => $normalizedRow['apellido_materno'],
                    'level' => $normalizedRow['nivel'],
                    'grade' => $normalizedRow['grado'],
                    'section' => $normalizedRow['seccion'],
                    'representativeDni' => $normalizedRow['nro_doc_responsable'],
                    'representativeNames' => $normalizedRow['nombre_apellido_responsable'],
                    'telephone' => isset($normalizedRow['telefono']) ? $normalizedRow['telefono'] : null,
                    'state' => 1,
                    'user_id' => $user->id,
                ]
            );
            $person->save();

            // Verificar y actualizar el estado de los estudiantes actuales
            static $importCompleted = false;
            if (!$importCompleted) {
                $importCompleted = true;
                foreach ($currentStudents as $student) {
                    if (!in_array($student->documentNumber, $importedDocumentNumbers)) {
                        $student->state = 0;
                        $student->save();
                    }
                }
            }

            // Retornar la persona
            return $person;
        } catch (Exception $e) {
            // Lanzar un error 500 en caso de cualquier excepción
            throw new HttpException(500, 'Error processing the Excel file: ' . $e->getMessage());
        }
    }
}
