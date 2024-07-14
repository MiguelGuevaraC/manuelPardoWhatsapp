<?php

namespace App\Imports;

use App\Models\Compromiso;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class CompromisoImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        
        $user = Auth::user();
        $student = Person::where('documentNumber', $row[1])->first();

        if ($student) {
            // Desactivar todos los compromisos actuales del estudiante

            // Crear o actualizar el compromiso para este estudiante
            $compromiso = Compromiso::updateOrCreate([
                'student_id' => $student->id,
                'cuotaNumber' => $row[11],
            ], [
                'cuotaNumber' => $row[11],
                'paymentAmount' => $row[12],
                'expirationDate' => $this->parseExcelDate($row[13]), // Suponiendo que tienes una función para parsear la fecha
                'conceptDebt' => $row[14],
                'status' => $row[15],
                'state' => 1, // Asegurar que siempre se establezca el estado como activo (1)
            ]);
        }else{
            
        }

        return $compromiso ?? null; // Devolver el compromiso creado o actualizado
    }

    // Función para parsear la fecha de Excel
    private function parseExcelDate($excelDate)
    {
        $excelBaseDate = strtotime('1899-12-30');
        $fecha = date('Y-m-d', $excelBaseDate + ($excelDate - 1) * 86400);
        return $fecha;
    }

}
