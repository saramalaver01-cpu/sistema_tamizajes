<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@uceva.edu.co'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'is_admin' => true,
            ]
        );

        $this->call([
            ModulosSeeder::class,
            PreguntasViolentometroSeeder::class,
            PreguntasBienestarEmocionalSeeder::class,
            PreguntasCargaAcademicaSeeder::class,
        ]);
    }

}
