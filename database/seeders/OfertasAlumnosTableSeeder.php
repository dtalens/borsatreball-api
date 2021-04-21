<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfertasAlumnosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ofertas_alumnos')->insert(['id'=>1,'id_oferta'=>1,'id_alumno'=>4,'interesado'=>1]);
        DB::table('ofertas_alumnos')->insert(['id'=>2,'id_oferta'=>1,'id_alumno'=>3,'interesado'=>0]);
     }
}
