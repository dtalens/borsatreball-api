<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="AlumneCicle",
 *     description="Informació del cicles d'un alumne",
 *     @OA\Xml(name="AlumnoCicloResource"),
 *     @OA\Property(
 *      property = "id_alumno",
 *      title="id_alumno",
 *      description="Identificador d'usuari",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "id_ciclo",
 *      title="id_ciclo",
 *      description="ID del cicle",
 *      example="5",
 *      type="integer"),
 *     @OA\Property(
 *      property = "any",
 *      title="any",
 *      description="Any acabament del cicle",
 *      example="2018",
 *      type="string"),
 *     @OA\Property(
 *      property = "validado",
 *      title="validado",
 *      description="El cicle ha estat validad per responsable",
 *      example="1",
 *      type="boolean"),
 *     )
 * )
 */
class AlumnoCicloResource extends JsonResource
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
            'id_alumno' => $this->pivot->id_alumno,
            'id_ciclo' => $this->pivot->id_ciclo,
            'any' => $this->pivot->any,
            'validado' => $this->pivot->validado,
        ];
    }
}
