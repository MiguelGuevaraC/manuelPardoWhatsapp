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
        $user = Auth::user();
        $currentStudents = $user->students;
        static $importedDocumentNumbers = [];
        $importedDocumentNumbers[] = $row[1];

        $person = Person::updateOrCreate(
            ['documentNumber' => $row[1]],
            [
                'typeofDocument' => $row[0],
                'names' => $row[2],
                'fatherSurname' => $row[3],
                'motherSurname' => $row[4],
                'level' => $row[5],
                'grade' => $row[6],
                'section' => $row[7],
                'representativeDni' => $row[9],
                'representativeNames' => $row[8],
                'telephone' => $row[10],
                'state' => 1,
                'user_id' => $user->id,
            ]
        );
        $person->save();

      
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
    }

}
