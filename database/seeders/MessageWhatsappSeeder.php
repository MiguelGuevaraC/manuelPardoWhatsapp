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
            'block1' => 'Estimado(a) {{nombreApoderado}} ({{dniApoderado}}), responsable de la cuota de pago del alumno {{nombreAlumno}} ({{codigoAlumno}}) de {{grado}} {{seccion}} del nivel de {{nivel}}.',
            'block2' => 'Actualmente tiene {{numCuotas}} pensiones vencidas de {{meses}}, por lo tanto le informamos que en el REGISTRO DE PAGOS SU INCUMPLIMIENTO ASCIENDE A {{montoPago}} SOLES, por ello le invitamos a regularizar dicho periodo a la brevedad posible, para no afectar de esa manera nuestros costos internos.',
            'block3' => 'Puede acercarse de manera presencial a las oficinas de administración de tesorería del Colegio de lunes a viernes de 07:40 a.m. a 03:45 p.m., ubicadas en la AV. Luis Gonzales 1415.',
            'block4' => 'Otras formas de pago: a través de la Plataforma Niubiz en el siguiente enlace: https://www.cmpardo.edu.pe/portal/index.php/pagar-con-niubiz/ También puede ver un video tutorial aquí: https://www.youtube.com/watch?v=un_nMn27EJo',
            'state' => true,
            'responsable_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
