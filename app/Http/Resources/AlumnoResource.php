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
 *      property = "domicilio",
 *      title="domicilio",
 *      description="Adreça de l'alumne",
 *      example="C/Cid 14",
 *      type="string"),
 *     @OA\Property(
 *      property = "info",
 *      title="info",
 *      description="Info de l'alumne",
 *      example="1",
 *      type="boolean"),
 *     @OA\Property(
 *      property = "bolsa",
 *      title="bolsa",
 *      description="bolsa",
 *      example="0",
 *      type="boolean"),
 *     @OA\Property(
 *      property = "cv_enlace",
 *      title="cv_enlace",
 *      description="Enllaç al curriculum de l'alumne",
 *      example="https://www.pepebotera.com",
 *      type="string") ,
 *     @OA\Property(
 *      property = "telefono",
 *      title="telefono",
 *      description="Telèfon de l'alumne",
 *      example="666666666",
 *      type="string"),
 *     @OA\Property(
 *      property = "email",
 *      title="email",
 *      description="Email de l'alumne",
 *      example="pepe.botera@gmail.com",
 *      type="string"),
 *     @OA\Property(
 *      property = "ciclos",
 *      type="array",
 *      @OA\Items(ref="#/components/schemas/AlumnoCicloResource")
 *      ),
 *     @OA\Property(
 *      property = "created_at",
 *      title="created_at",
 *      description="Hora de creació registre",
 *      example="2022-03-17 19:37:34",
 *      type="time"),
 *     @OA\Property(
 *      property = "updated_at",
 *      title="updated_at",
 *      description="Hora de modificació registre",
 *      example="2022-03-17 19:37:34",
 *      type="time")
 * )
 */





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
            'ciclos' => AlumnoCicloResource::collection($this->ciclos),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


}
