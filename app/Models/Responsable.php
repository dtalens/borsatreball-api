<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;



class Responsable extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'id', 'nombre','apellidos'
    ];

    public function Ciclos()
    {
        return $this->hasMany(Ciclo::class,'responsable');
    }

    public function User()
    {
        return $this->hasOne(User::class,'id');
    }

    public function getFullNameAttribute(){
        return $this->nombre.' '.$this->apellidos;
    }


}
