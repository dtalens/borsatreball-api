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
        User::create(['id'=>1,'email'=>'igomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
        ,'rol'=>'2','active'=>'1']);
        User::create(['id'=>2,'email'=>'amiro@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'3','active'=>'1']);
        User::create(['id'=>3,'email'=>'jgomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'7','active'=>'1']);
        User::create(['id'=>4,'email'=>'agomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'7','active'=>'1']);
        User::create(['id'=>5,'email'=>'fgomis@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'5','active'=>'1']);
        User::create(['id'=>6,'email'=>'amullor@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'5','active'=>'1']);
        User::create(['id'=>7,'email'=>'noOfertas@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'5','active'=>'1']);
        User::create(['id'=>8,'email'=>'boti@cipfpbatoi.es','password'=>'$2y$10$DpPd5Ioe0dnEuwQq0MnF1ONoojBGsHWDF4YO.Wly3lt08G6S.URsO'
            ,'rol'=>'3','active'=>'1']);
    }
}
