<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnosCiclosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alumnos_ciclos')->insert(['id'=>1,'id_alumno'=>3,'id_ciclo'=>2,'any'=>'2016','validado'=>1]);
        DB::table('alumnos_ciclos')->insert(['id'=>2,'id_alumno'=>3,'id_ciclo'=>3,'any'=>'2018','validado'=>1]);
        DB::table('alumnos_ciclos')->insert(['id'=>3,'id_alumno'=>3,'id_ciclo'=>12,'validado'=>1]);
        DB::table('alumnos_ciclos')->insert(['id'=>4,'id_alumno'=>4,'id_ciclo'=>4,'any'=>'2016','validado'=>1]);
        DB::table('alumnos_ciclos')->insert(['id'=>5,'id_alumno'=>4,'id_ciclo'=>12,'any'=>'2018','validado'=>1]);
        DB::table('alumnos_ciclos')->insert(['id'=>6,'id_alumno'=>4,'id_ciclo'=>2,'validado'=>1]);
    }
}
