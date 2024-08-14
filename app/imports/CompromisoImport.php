<?php
namespace App\Imports;

use App\Models\Compromiso;
use App\Models\Person; // Asegúrate de importar el modelo Person
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompromisoImport implements ToModel, WithHeadingRow
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
                'código' => 'codigo',
                'ngs' => 'ngs',
                'concepto' => 'concepto',
                'ene' => 'ene',
                'feb' => 'feb',
                'mar' => 'mar',
                'abr' => 'abr',
                'may' => 'may',
                'jun' => 'jun',
                'jul' => 'jul',
                'ago' => 'ago',
                'set' => 'set',
                'oct' => 'oct',
                'nov' => 'nov',
                'dic' => 'dic',
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
                return null;
                // Retornar null porque esta fila no contiene datos de compromisos
            }

            // Crear un array con los datos normalizados
            $normalizedRow = [];
            foreach ($this->headerMap as $columnName => $key) {
                $normalizedRow[$columnName] = isset($row[$key]) ? $row[$key] : null;
            }

            if (!preg_match('/^\d+$/', $normalizedRow['codigo'])) {
                return null;
            }

            // Buscar al estudiante en la base de datos utilizando el número de documento
            $student = Person::where('documentNumber', $normalizedRow['codigo'])->first();

            if ($student) {
                // Crear compromisos de pago sumando los montos de cada mes
                $months = [
                    'ene' => 'Enero',
                    'feb' => 'Febrero',
                    'mar' => 'Marzo',
                    'abr' => 'Abril',
                    'may' => 'Mayo',
                    'jun' => 'Junio',
                    'jul' => 'Julio',
                    'ago' => 'Agosto',
                    'set' => 'Septiembre',
                    'oct' => 'Octubre',
                    'nov' => 'Noviembre',
                    'dic' => 'Diciembre',
                ];

                $totalAmount = 0;
                $cuotaNumber = 0;
                $conceptMonths = [];

                foreach ($months as $month => $fullMonthName) {
                    if (isset($normalizedRow[$month]) && $normalizedRow[$month] != '' &&
                        $normalizedRow[$month] != '0.00' && $normalizedRow[$month] != 0) {
                        $totalAmount += $normalizedRow[$month];
                        $cuotaNumber++;
                        $conceptMonths[] = $fullMonthName;
                    }
                }

                if ($totalAmount > 0) {
                   
                    // Crear o actualizar el compromiso asegurando que sea único por estudiante
                    $compromiso = Compromiso::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            //    'cuotaNumber' => $cuotaNumber,
                        ],
                        [
                            'cuotaNumber' => $cuotaNumber,
                            'paymentAmount' => $totalAmount,
                            'expirationDate' => null,
                            'conceptDebt' => '' . implode(', ', $conceptMonths),
                            'status' => 'Pendiente',
                            'state' => 1,
                            'telephoneStudent' => $student->telephone,
                        ]
                    );

                    $compromiso->save();
                }
            }

            // Retornar null para saltar a la siguiente fila
            return null;
        } catch (Exception $e) {
            // Lanzar un error 500 en caso de cualquier excepción
            throw new HttpException(500, 'Error processing the Excel file: ' . $e->getMessage());
        }
    }

    // public function model(array $row)
    // {
    //     try {
    //         // Definir los encabezados esperados
    //         $expectedColumns = [
    //             'código' => 'codigo',
    //             'ngs' => 'ngs',
    //             'concepto' => 'concepto',
    //             'ene' => 'ene',
    //             'feb' => 'feb',
    //             'mar' => 'mar',
    //             'abr' => 'abr',
    //             'may' => 'may',
    //             'jun' => 'jun',
    //             'jul' => 'jul',
    //             'ago' => 'ago',
    //             'set' => 'set',
    //             'oct' => 'oct',
    //             'nov' => 'nov',
    //             'dic' => 'dic',
    //         ];

    //         // Si el mapeo de encabezados está vacío, significa que estamos en la fila de encabezado
    //         if (empty($this->headerMap)) {
    //             foreach ($row as $key => $value) {
    //                 if ($value != null) {
    //                     $normalizedKey = strtolower(str_replace(' ', '', $value));

    //                     if (array_key_exists($normalizedKey, $expectedColumns)) {
    //                         $this->headerMap[$expectedColumns[$normalizedKey]] = $key;
    //                     }
    //                 }
    //             }
    //             return null;
    //             // Retornar null porque esta fila no contiene datos de compromisos
    //         }

    //         // Crear un array con los datos normalizados
    //         $normalizedRow = [];
    //         foreach ($this->headerMap as $columnName => $key) {
    //             $normalizedRow[$columnName] = isset($row[$key]) ? $row[$key] : null;
    //         }

    //         if (!preg_match('/^\d+$/', $normalizedRow['codigo'])) {

    //             return null;
    //         }

    //         // Buscar al estudiante en la base de datos utilizando el número de documento
    //         $student = Person::where('documentNumber', $normalizedRow['codigo'])->first();

    //         if ($student) {

    //             // Crear compromisos de pago para cada mes
    //             $months = [
    //                 'ene' => 'Enero',
    //                 'feb' => 'Febrero',
    //                 'mar' => 'Marzo',
    //                 'abr' => 'Abril',
    //                 'may' => 'Mayo',
    //                 'jun' => 'Junio',
    //                 'jul' => 'Julio',
    //                 'ago' => 'Agosto',
    //                 'set' => 'Septiembre',
    //                 'oct' => 'Octubre',
    //                 'nov' => 'Noviembre',
    //                 'dic' => 'Diciembre',
    //             ];

    //             foreach ($months as $month => $fullMonthName) {
    //                 if (isset($normalizedRow[$month]) && $normalizedRow[$month] != '') {

    //                     // Calcular el número de cuota
    //                     $cuotaNumber = array_search($month, array_keys($months)) + 1;

    //                     // Actualizar o crear el compromiso asegurando que sea único por estudiante y mes
    //                     $compromiso = Compromiso::updateOrCreate(
    //                         [
    //                             'student_id' => $student->id,
    //                             'cuotaNumber' => $cuotaNumber,
    //                         ],
    //                         [
    //                             'paymentAmount' => $normalizedRow[$month],
    //                             'expirationDate' => null,
    //                             'conceptDebt' => 'Pagos del mes de: ' . $fullMonthName,
    //                             'status' => 'Pendiente',
    //                             'telephoneStudent' => $student->telephone,
    //                         ]
    //                     );

    //                     $compromiso->save();
    //                 }
    //             }
    //         }

    //         // Retornar null para saltar a la siguiente fila
    //         return null;
    //     } catch (Exception $e) {
    //         // Lanzar un error 500 en caso de cualquier excepción
    //         throw new HttpException(500, 'Error processing the Excel file: ' . $e->getMessage());
    //     }
    // }
}
