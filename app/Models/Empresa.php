<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Collection;

class Empresa extends Model
{
    public $timestamps = true;

    protected $fillable = ['id','cif','nombre','domicilio','localidad','contacto','telefono','web','descripcion'];

    public function User()
    {
        return $this->hasOne(User::class,'id');
    }

    public function Ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_empresa','id');
    }





}
