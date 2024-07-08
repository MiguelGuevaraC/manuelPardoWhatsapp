<?php

namespace App\Imports;

use App\Models\Person;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class PersonImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {


        return Person::updateOrCreate(
            ['documentNumber' => $row[1]],
            [
                'typeofDocument' => $row[0], // O ajusta según sea necesario
                'names' => $row[2],
                'fatherSurname' => $row[3],
                'motherSurname' => $row[4],
                'level' => $row[5],
                'grade' => $row[6],
                'section' => $row[7],
                'representativeDni' => $row[8],
                'representativeNames' => $row[9],
                'telephone' => $row[10],
                'state' => 1,
                'user_id' => Auth::user()->id,

            ]
        );
    }


}
