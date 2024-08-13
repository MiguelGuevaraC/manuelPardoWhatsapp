<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Person;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $array = [
            [
                'id' => '1',
                'typeofDocument' => 'DNI',
        

                'documentNumber' => '11111110',
                'names' => 'Administrador',
                'fatherSurname' => '-',
                'motherSurname' => '-',
                'businessName' => null,
                'level' => null,
                'grade' => null,
                'section' => null,
                'representativeDni' => null,
                'representativeNames' => null,
                'telephone' => '903017426',
                'email' => 'johndoe@gmail.com',
                'origin' => 'Lambayeque',
                'ocupation' => 'Administrador',
            ],
            [
                'id' => '2',
                'typeofDocument' => 'DNI',
             

                'documentNumber' => '00000000',
                'names' => 'VARIOS',
                'fatherSurname' => '-',
                'motherSurname' => '-',
                'businessName' => null,
                'level' => null,
                'grade' => null,
                'section' => null,
                'representativeDni' => null,
                'representativeNames' => null,
                'telephone' => '903017426',
                'email' => 'johndoe@gmail.com',
                'origin' => 'Lambayeque',
                'ocupation' => 'VARIOS',
            ],
            [
                'id' => '3',
                'typeofDocument' => 'DNI',
               

                'documentNumber' => '11111111',
                'names' => 'Administrador',
                'fatherSurname' => '-',
                'motherSurname' => '-',
                'businessName' => null,
                'level' => null,
                'grade' => null,
                'section' => null,
                'representativeDni' => null,
                'representativeNames' => null,
                'telephone' => '903017426',
                'email' => 'johndo@gmail.com',
                'origin' => 'Lambayeque',
                'ocupation' => 'Administrador',
            ],
            [
                'id' => '4',
                'typeofDocument' => 'RUC',
                'documentNumber' => '20103416982',
                

                'names' => null,
                'fatherSurname' => null,
                'motherSurname' => null,
                'businessName' => 'COLEGIO MANUEL PARDO',
                'level' => null,
                'grade' => null,
                'section' => null,
                'representativeDni' => null,
                'representativeNames' => null,
                'telephone' => '903017426',
                'email' => 'manuelpardo@gmail.com',
                'origin' => 'Chiclayo',
                'ocupation' => 'Cliente',
            ], [
                'id' => '5',
                'typeofDocument' => 'DNI',
                

                'documentNumber' => '22222222',
                'names' => 'Estudiante',
                'fatherSurname' => 'Padre',
                'motherSurname' => 'Madre',
                'businessName' => null,
                'level' => 'Secundaria',
                'grade' => '3ro',
                'section' => 'A',
                'representativeDni' => '33333333',
                'representativeNames' => 'Padre de Estudiante',
                'telephone' => '987654321',
                'email' => 'padredeestudiante@gmail.com',
                'origin' => 'Lima',
                'ocupation' => 'Estudiante',
            ],
        ];

        foreach ($array as $object) {
            $typeOfuser1 = Person::find($object['id']);
            if ($typeOfuser1) {
                $typeOfuser1->update($object);
            } else {
                Person::create($object);
            }
        }

        $this->call(GroupMenuSeeder::class);
        $this->call(TypeUserSeeder::class);

        $this->call(OptionMenuSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AccessSeeder::class);
        $this->call(MessageWhatsappSeeder::class);
        $this->call(WhatsappSendSeeder::class);
    }
}
