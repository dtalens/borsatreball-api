<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['email'=>'igomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
        ,'rol'=>'2','active'=>'1']);
        User::create(['email'=>'fgomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'5','active'=>'1']);
        User::create(['email'=>'agomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'7','active'=>'1']);
    }
}
