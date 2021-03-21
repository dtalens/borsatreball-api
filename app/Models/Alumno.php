<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;



class Alumno extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'id', 'nombre','apellidos','domicilio', 'telefono','info',
        'bolsa','cv_enlace'
    ];

    public function Ciclos()
    {
        return $this->belongsToMany(Ciclo::class,'alumnos_ciclos', 'id_alumno',
            'id_ciclo', 'id', 'id')->withPivot(['any','validado']);
    }


    public function CiclosValidos()
    {
        return $this->belongsToMany(Ciclo::class,'alumnos_ciclos', 'id_alumno',
            'id_ciclo', 'id', 'id')
            ->wherePivot('validado',1)
            ->withPivot('any');
    }

    public function CiclosNoValidos()
    {
        return $this->belongsToMany(Ciclo::class,'alumnos_ciclos', 'id_alumno',
            'id_ciclo', 'id', 'id')
            ->wherePivot('validado',0)
            ->withPivot('any');
    }
    public function Ofertas()
    {
        return $this->belongsToMany(Oferta::class,'ofertas_alumnos', 'id_alumno', 'id_oferta', 'id', 'id')->withPivot('interesado');
    }


    public function User()
    {
        return $this->hasOne(User::class,'id');
    }

    public function getFullNameAttribute(){
        return $this->nombre.' '.$this->apellidos;
    }

    public static function BelongsToCicles($ciclos){
        $alumnos = new Collection();
        foreach ($ciclos as $ciclo){
            foreach ($ciclo->alumnos as $alumno)
                if (!$alumnos->contains($alumno))
                 $alumnos->add($alumno);
        }
        return $alumnos;
    }
    public static function InterestedIn($empresa){
        //Los alumnos que estan interesados en alguna oferta
        $empresa = Empresa::find($empresa);
        $ofertas = $empresa->Ofertas->where('archivada',0);
        $alumnos = new Collection();
        foreach ($ofertas as $oferta){
            foreach ($oferta->Alumnos->where('pivot.interesado',1) as $alumno){
                if (!$alumnos->contains($alumno)) $alumnos->add($alumno);
            }

        }
        return $alumnos;
    }

}
