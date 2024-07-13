<?php

namespace App\Imports;

use App\Models\Compromiso;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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

        $person = Person::where('documentNumber', $row[1])->first();
     
        $excelDateNumber = $row[13];
        $excelBaseDate = strtotime('1899-12-30');
        $fecha = date('Y-m-d', $excelBaseDate + ($excelDateNumber - 1) * 86400);
        if ($person) {
            try {
                $compromiso = Compromiso::updateOrCreate([
                    'cuotaNumber' => $row[11],
                    'paymentAmount' => $row[12],
                    'expirationDate' => $fecha,
                    'conceptDebt' => $row[14],
                    'status' => $row[15],
                    'student_id' => $person->id,
                ]);

            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

        }

        return $compromiso;
    }
}
