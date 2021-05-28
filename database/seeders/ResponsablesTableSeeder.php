<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Responsable;

class ResponsablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Responsable::create(['id'=>2,'nombre'=>'Aitana','apellidos'=>'Miró Sánchez']);
        Responsable::create(['id'=>8,'nombre'=>'Mari Carmen','apellidos'=>'Boti']);
    }
}
