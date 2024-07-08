<?php

namespace App\Exports;

use App\Models\Person;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;

class PersonExport implements FromCollection
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {

        return collect(Person::all());
    }

    public function headings(): array
    {
        // Define los encabezados de las columnas
        return [];
    }

}
