<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call(ModulosSeeder::class);
        $this->call([ModulosSeeder::class, PreguntasViolentometroSeeder::class, ContextosViolentometroSeeder::class,]);
        $this->call([ModulosSeeder::class, PreguntasViolentometroSeeder::class, PreguntasBienestarEmocionalSeeder::class,]);
        $this->call([ModulosSeeder::class, PreguntasViolentometroSeeder::class, PreguntasBienestarEmocionalSeeder::class, PreguntasCargaAcademicaSeeder::class,]);
    }

}
