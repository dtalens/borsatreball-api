<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumno;

class AlumnosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alumn = Alumno::create(['id'=>3,'nombre'=>'Altea','apellidos'=>'Gomis Miró','domicilio'=>'Pârtida Algars',
        'telefono'=>'655556777','info'=>1,'bolsa'=>'1']);
        Alumno::create(['id'=>4,'nombre'=>'Aitana','apellidos'=>'Miró Sánchez','domicilio'=>'Pârtida Algars',
            'telefono'=>'655556777','info'=>1,'bolsa'=>'1']);

    }
}
