<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfertasCiclosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ofertas_ciclos')->insert(['id'=>1,'id_oferta'=>1,'id_ciclo'=>2]);
        DB::table('ofertas_ciclos')->insert(['id'=>2,'id_oferta'=>1,'id_ciclo'=>3]);
        DB::table('ofertas_ciclos')->insert(['id'=>3,'id_oferta'=>2,'id_ciclo'=>12]);
     }
}
