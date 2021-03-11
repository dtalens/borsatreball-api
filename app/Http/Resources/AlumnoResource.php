<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlumnoResource extends JsonResource
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
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'domicilio' => $this->domicilio,
            'info' => $this->info,
            'bolsa' => $this->bolsa,
            'cv_enlace' => $this->cv_enlace,
            'telefono' => $this->telefono,
            'email' => $this->User->email,
            'ciclos' => hazArray($this->ciclos,'id', 'pivot'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
