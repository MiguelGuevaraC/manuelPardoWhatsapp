<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['id' => '1', 'username' => 'adminBack', 'password' => 'adminBack123', 'person_id' => '1', 'typeofUser_id' => '1'],
            ['id' => '2', 'username' => 'admin', 'password' => 'admin321', 'person_id' => '3', 'typeofUser_id' => '2'],
            ['id' => '3', 'username' => 'ManuelPardo', 'password' => 'ManuelPardo2024', 'person_id' => '4', 'typeofUser_id' => '3'],
        ];

        foreach ($users as $user) {
            // Buscar el registro por su ID
            $user1 = User::find($user['id']);

            // Hashear la contraseña
            $user['password'] = Hash::make($user['password']);

            // Si el usuario existe, actualizarlo; de lo contrario, crear uno nuevo
            if ($user1) {
                $user1->update($user);
            } else {
                User::create($user);
            }
        }
    }
}
