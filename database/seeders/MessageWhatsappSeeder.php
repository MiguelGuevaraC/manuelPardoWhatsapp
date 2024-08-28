<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageWhatsappSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('message_whasapps')->insert([
            
            'title' => 'YA TIENES {{numCuotas}} PENSIONES VENCIDAS',
            'block1' => 'Estimado(a) {{nombreApoderado}} ({{dniApoderado}}), responsable del pago de {{nombreAlumno}} ({{codigoAlumno}}), de {{grado}} {{seccion}}.',
            'block2' => 'Puede visitar las oficinas de administración de tesorería del Colegio en AV. Luis Gonzales 1415, de lunes a viernes, de 07:40 a.m. a 03:45 p.m. También puede pagar a través de Niubiz: https://www.cmpardo.edu.pe/web/pagar-con-niubiz/',
            'block3' => 'Puede acercarse de manera presencial a las oficinas de administración de tesorería del Colegio de lunes a viernes de 07:40 a.m. a 03:45 p.m., ubicadas en la AV. Luis Gonzales 1415.',
            'block4' => 'Tutorial Niubiz : https://www.youtube.com/watch?v=un_nMn27EJo PAGO EFECTIVO: Otra forma de pago, se encuentra en la Intranet del SIE WEB en la opción del menú Pagar.Tutorial Pago Efectivo : https://www.youtube.com/watch?v=p4U7qGlfJiU',
            'state' => true,
            'responsable_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
