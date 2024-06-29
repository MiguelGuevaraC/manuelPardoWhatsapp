<?php

namespace Database\Seeders;

use App\Models\GroupMenu;
use Illuminate\Database\Seeder;

class GroupMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $array = [
        //     ['id' => '1', 'name' => 'Mantenimientos', 'icon' => 'build'],
        //     ['id' => '2', 'name' => 'Movimientos', 'icon' => 'swap_horiz'],
        //     ['id' => '3', 'name' => 'Reporte', 'icon' => 'bar_chart'],
        //     ['id' => '4', 'name' => 'Seguridad', 'icon' => 'security'],
        // ];
        
        $array = [
            ['id' => '1', 'name' => 'Mantenimiento', 'icon' => 'fa fa-cogs'],
            ['id' => '2', 'name' => 'Movimientos', 'icon' => 'fa fa-exchange-alt'],
            ['id' => '3', 'name' => 'Reporte', 'icon' => 'fa fa-chart-bar'],
            ['id' => '4', 'name' => 'Seguridad', 'icon' => 'fa fa-shield-alt'],
        ];
        

        foreach ($array as $object) {
            $typeOfuser1 = GroupMenu::find($object['id']);
            if ($typeOfuser1) {
                $typeOfuser1->update($object);
            } else {
                GroupMenu::create($object);
            }
        }
    }
}
