<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresa::create(['id'=>5,'cif'=>'A12345678','nombre'=>'Aitex','localidad'=>'Alcoi','domicilio'=>'Pârtida Algars',
            'telefono'=>'655556777','web'=>'http://www.aitex.es']);
        Empresa::create(['id'=>6,'cif'=>'A12345678','nombre'=>'AIJU','localidad'=>'Alcoi','domicilio'=>'Pârtida Algars',
            'telefono'=>'655556777','web'=>'http://www.aiju.es']);

    }
}
