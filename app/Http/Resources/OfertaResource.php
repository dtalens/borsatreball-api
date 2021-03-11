<?php

namespace App\Http\Resources;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OfertaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
             'id'=>$this->id,
             'id_empresa' => $this->id_empresa,
             'descripcion' => $this->descripcion,
             'puesto' => $this->puesto,
             'tipo_contrato' => $this->tipo_contrato,
             'activa' => $this->activa,
             'contacto' => $this->contacto,
             'telefono' => $this->telefono,
             'email' => $this->email,
             'mostrar_contacto' => $this->mostrar_contacto,
             'validada' => $this->validada,
             'any' => $this->any,
             'estudiando' => $this->estudiando,
             'archivada' => $this->archivada,
             'ciclos' => hazArray($this->ciclos,'id','pivot'),
             'empresa' => $this->empresa,
             'interesado' => $this->when(AuthUser()->isAlumno() , $this->getInterested()),
             'alumnos' => $this->when(!AuthUser()->isAlumno(), AlumnoResource::collection($this->alumnos)),
             'created_at' => $this->created_at,
             'updated_at' => $this->updated_at,
        ];
    }
    private function getInterested(){
        if ($this->alumnos->where('id',AuthUser()->id)->count()==0) return null;
        return $this->alumnos->where('id',AuthUser()->id)->first()->pivot->interesado;
    }





}
