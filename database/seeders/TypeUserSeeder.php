<?php

namespace Database\Seeders;

use App\Models\TypeUser;
use Illuminate\Database\Seeder;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['id' => '1', 'name' => 'Administrador Backend'],
            ['id' => '2', 'name' => 'Administrador'],
            ['id' => '3', 'name' => 'Cliente'],
        ];

        foreach ($array as $object) {
            $typeOfuser1 = TypeUser::find($object['id']);
            if ($typeOfuser1) {
                $typeOfuser1->update($object);
            } else {
                TypeUser::create($object);
            }
        }

    }
}
