<?php

namespace Database\Seeders;

use App\Models\Optionmenu;
use Illuminate\Database\Seeder;

class OptionMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            ['id' => '1', 'name' => 'Migraciones', 'route' => 'migracion', 'groupmenu_id' => 1, 'icon' => 'fa fa-exchange'],
            ['id' => '2', 'name' => 'Estudiantes', 'route' => 'estudiante', 'groupmenu_id' => 2, 'icon' => 'fa fa-graduation-cap'],
            // ['id' => '3', 'name' => 'Mensajería', 'route' => 'mensajeria2', 'groupmenu_id' => 2, 'icon' => 'fa fa-envelope'],
            ['id' => '4', 'name' => 'Reporte Mensajería', 'route' => 'mensajeria', 'groupmenu_id' => 3, 'icon' => 'fa-solid fa-file-circle-check'],
            ['id' => '5', 'name' => 'Gestionar Accesos', 'route' => 'access', 'groupmenu_id' => 4, 'icon' => 'fa fa-lock'],
           
            ['id' => '6', 'name' => 'Usuario', 'route' => 'user', 'groupmenu_id' => 4, 'icon' => 'fa fa-user'],
            ['id' => '7', 'name' => 'Compromisos', 'route' => 'compromiso', 'groupmenu_id' => 2, 'icon' => 'fa-solid fa-receipt'],
            
        ];

        foreach ($array as $object) {
            $typeOfuser1 = Optionmenu::find($object['id']);
            if ($typeOfuser1) {
                $typeOfuser1->update($object);
            } else {
                Optionmenu::create($object);
            }
        }
    }
}
