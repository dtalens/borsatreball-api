<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;





class Ciclo extends Model
{
    public $timestamps = false;
    protected $guarded = [];


    public function Ofertas()
    {
        return $this->belongsToMany(Oferta::class,'ofertas_ciclos', 'id_ciclo', 'id_oferta', 'id', 'id');
    }

    public function Responsable()
    {
        return $this->hasOne(User::class,'id','responsable');
    }

    public function Alumnos()
    {
        return $this->belongsToMany(Alumno::class,'alumnos_ciclos', 'id_ciclo',
            'id_alumno', 'id', 'id')->withPivot(['any','validado']);
    }
}
