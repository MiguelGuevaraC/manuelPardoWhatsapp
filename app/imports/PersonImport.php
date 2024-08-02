<?php

namespace App\imports;

use App\Models\Person;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
                'celularresponsabledepago' => 'telefono_responsable_pago',
                'telefonodelapoderado' => 'telefono_apoderado',
                'telefonomadre' => 'telefono_madre',
                'celularmadre' => 'celular_madre',
                'telefonopadre' => 'telefono_padre',
                'celularpadre' => 'celular_padre',
                'telefono' => 'telefono',
            ];

            // Si el mapeo de encabezados está vacío, significa que estamos en la fila de encabezado
            if (empty($this->headerMap)) {
                foreach ($row as $key => $value) {
                    if (!empty($value)) {
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
                $normalizedRow[$columnName] = isset($row[$key]) ? trim($row[$key]) : null;
            }

            // Validar el campo nro_doc_responsable
            $nroDocResponsable = $normalizedRow['nro_doc_responsable'];
            if (empty($nroDocResponsable) || !is_numeric($nroDocResponsable) || strlen($nroDocResponsable) < 6) {
                // Log::warning('IMPORTACION: Invalid nro_doc_responsable value: ' . var_export($nroDocResponsable, true));
                // return null; // Omitir la fila con valor inválido en nro_doc_responsable
            }

            // Combinar "nombre_responsable_pago" y "apellido_materno_responsable"
            $normalizedRow['nombre_apellido_responsable'] = trim($normalizedRow['nombre_responsable_pago'] . ' ' . $normalizedRow['apellido_materno_responsable']);

            // Verificar que las columnas no sean nulas
            foreach ($expectedColumns as $columnName => $dbColumnName) {
                if (is_null($normalizedRow[$dbColumnName]) && $dbColumnName != 'apellido_materno_responsable') {
                    // Log::warning('IMPORTACION: Null value found in required columns: ' . $dbColumnName);
                    // return null; // Omitir la fila con valor nulo en una columna requerida
                }
            }

            $phoneFields = [
                // 'telefono_responsable_pago',

                'celular_madre',
                'telefono_apoderado',
                'celular_padre',

                'telefono_padre',
                'telefono_madre',
                'telefono',
            ];
            $cleanedPhoneNumber = $normalizedRow['telefono_responsable_pago'];
            if ($this->isValidPhoneNumber($cleanedPhoneNumber) == false) {

                $cleanedPhoneNumber = $this->cleanPhoneNumber($normalizedRow, $phoneFields);

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
                    'representativeDni' => $nroDocResponsable ?? '', // Use empty string if null
                    'representativeNames' => $normalizedRow['nombre_apellido_responsable'],
                    'telephone' => $cleanedPhoneNumber,
                    'state' => 1,
                    'user_id' => $user->id,
                ]
            );

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
            Log::error('IMPORTACION: ' . $e->getMessage());
            throw new HttpException(500, 'Error processing the Excel file: ' . $e->getMessage());
        }
    }

    private function cleanPhoneNumber(array $row, array $fields): ?string
    {
        // Variable para almacenar el primer número válido encontrado
        $firstValidNumber = null;

        foreach ($fields as $field) {
            if (isset($row[$field]) && !empty($row[$field])) {
                // Eliminar caracteres no numéricos y espacios
                $cleanedString = preg_replace('/[^0-9]/', '', $row[$field]);

                // Buscar todos los números de 9 dígitos
                preg_match_all('/\d{9}/', $cleanedString, $matches);

                // Verificar si se encontró algún número de 9 dígitos
                if (!empty($matches[0])) {
                    $firstValidNumber = $matches[0][0]; // Guardar el primer número de 9 dígitos encontrado
                    break; // Salir del bucle una vez que se encuentra un número válido
                }
            }
        }

        return $firstValidNumber; // Retornar el primer número válido o null si no se encontró ninguno
    }

    public function isValidPhoneNumber($string)
    {
        $length = strlen($string);

        $isOnlyDigits = ctype_digit($string);

        return $length === 9 && $isOnlyDigits;
    }

}
