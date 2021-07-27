<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Collection;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\DB;


class Oferta extends Model
{
    public $timestamps = true;
    protected $table = 'ofertas';
    protected $fillable = [
            'id', 'id_empresa','descripcion','puesto','tipo_contrato', 'activa','contacto','mostrar_contacto',
            'telefono','email', 'any','estudiando','archivada'
        ];

    public function Ciclos()
    {
        return $this->belongsToMany(Ciclo::class,'ofertas_ciclos', 'id_oferta', 'id_ciclo', 'id', 'id');
    }
    public function Alumnos()
    {
        return $this->belongsToMany(Alumno::class,'ofertas_alumnos', 'id_oferta', 'id_alumno', 'id', 'id');
    }
    public function Empresa(){
        return $this->belongsTo(Empresa::class,'id_empresa');
    }

    public function scopeBelongsToEnterprise($query,$idEmpresa){
        return $query->where('id_empresa', $idEmpresa);
    }

    public static function BelongsToCicles($ciclos){
        $ofertas = new Collection();
        foreach ($ciclos as $ciclo){
            foreach ($ciclo->ofertas as $oferta)
                if (!$ofertas->contains($oferta)) $ofertas->add($oferta);
        }
        return $ofertas;
    }





}
