<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciclo;
use Illuminate\Support\Facades\File;

class CiclosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(__DIR__ . '/ciclos.json');
        $data = json_decode($json);

        foreach ($data as $item){
            Ciclo::create(array(
                'id' => $item->id,
                'codigo' => $item->codigo,
                'ciclo' => $item->ciclo,
                'Dept' => $item->Dept,
                'cDept' => $item->cDept,
                'vDept' => $item->vDept,
                'responsable' => $item->responsable,
                'vCiclo' => $item->vCiclo,
                'cCiclo' => $item->cCiclo,
            ));

        }
    }
}
