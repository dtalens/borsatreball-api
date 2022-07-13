<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Alumne",
 *     description="Dades d'alumne",
 *     @OA\Xml(name="Alumno"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador d'usuari",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "nombre",
 *      title="nombre",
 *      description="Nom de l'alumne",
 *      example="Jose Manuel",
 *      type="string"),
 *     @OA\Property(
 *      property = "apellidos",
 *      title="apelllidos",
 *      description="Cognoms de l'alumne",
 *      example="Miró García",
 *      type="string"),
 *     @OA\Property(
 *      property = "ciclos",
 *      type="array",
 *      @OA\Items(ref="#/components/schemas/KeyResource")
 *      )
 * )
 */





class ResponsableResource extends JsonResource
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
            'email' => $this->User->email,
            'ciclos' => KeyResource::collection($this->Ciclos)
        ];
    }


}
