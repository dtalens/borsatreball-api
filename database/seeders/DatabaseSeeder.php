<?php

namespace Database\Seeders;

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
        $this->call(PersonalTokenSeeder::class);
        $this->call(CiclosTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AlumnosTableSeeder::class);
        $this->call(EmpresasTableSeeder::class);
        $this->call(OfertasTableSeeder::class);
        $this->call(AlumnosCiclosTableSeeder::class);
        $this->call(OfertasCiclosTableSeeder::class);
        $this->call(OfertasAlumnosTableSeeder::class);
    }
}
