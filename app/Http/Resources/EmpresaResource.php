<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Empresa",
 *     description="Dades d'empresa",
 *     @OA\Xml(name="Empresa"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador d'empresa",
 *      example="6",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "cif",
 *      title="cif",
 *      description="CIF de l'empresa",
 *      example="B14567890",
 *      type="string"),
 *     @OA\Property(
 *      property = "nombre",
 *      title="nombre",
 *      description="Nom de l'empresa",
 *      example="AITEX",
 *      type="string"),
 *     @OA\Property(
 *      property = "domicilio",
 *      title="domicilio",
 *      description="Adreça de l'alumne",
 *      example="C/Cid 14",
 *      type="string"),
 *     @OA\Property(
 *      property = "telefono",
 *      title="telefono",
 *      description="Telèfon de l'empresa",
 *      example="666666666",
 *      type="string"),
 *     @OA\Property(
 *      property = "email",
 *      title="email",
 *      description="Email de l'empresa",
 *      example="info@aitex.es",
 *      type="string"),
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


class EmpresaResource extends JsonResource
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
            'cif' => $this->cif,
            'nombre' => $this->nombre,
            'domicilio' => $this->domicilio,
            'localidad' => $this->localidad,
            'contacto' => $this->contacto,
            'telefono' => $this->telefono,
            'email' => $this->User->email,
            'web' => $this->web,
            'descripcion' => $this->descripcion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
