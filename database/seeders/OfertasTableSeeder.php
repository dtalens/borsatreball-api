<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Oferta;

class OfertasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Oferta::create(['id'=>1,'id_empresa'=>'5','validada'=>1,'estudiando'=>1,
            'activa'=>'1']);
        Oferta::create(['id'=>2,'id_empresa'=>'5','validada'=>1,'estudiando'=>0,
            'activa'=>'1']);
        Oferta::create(['id'=>3,'id_empresa'=>'6','validada'=>1,'estudiando'=>0,
            'activa'=>'0','archivada'=>1]);

    }
}
