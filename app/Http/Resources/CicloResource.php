<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Cicle",
 *     description="Informació del cicles d'un alumne",
 *     @OA\Xml(name="CicleResource"),
 *     @OA\Property(
 *      property = "id",
 *      title="ID cicle",
 *      description="Identificador de cicle",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "codigo",
 *      title="codigo",
 *      description="codi intranet",
 *      example="CFMX",
 *      type="string"),
 *     @OA\Property(
 *      property = "ciclo",
 *      title="Codi del cicle llarg",
 *      description="Codi del cicle llarg",
 *      example="CFM FCT ESTÈTICA (LOGSE)",
 *      type="string"),
 *     @OA\Property(
 *      property = "Dept",
 *      title="Departament",
 *      description="Departament del cicle",
 *      example="Img",
 *      type="string"),
 *     @OA\Property(
 *      property = "cDept",
 *      title="Departament en castellà",
 *      description="Nom del departament en castellà",
 *      example="Imagen Personal",
 *      type="string"),
 *     @OA\Property(
 *      property = "vDept",
 *      title="Departament en valencià",
 *      description="Nom del departament en valencià",
 *      example="Imatge Personal",
 *      type="string"),
 *     @OA\Property(
 *      property = "reponsable",
 *      title="Codi usuari",
 *      description="Codi usuari reponsable del cicle",
 *      example="216",
 *      type="integer"),
 *     @OA\Property(
 *      property = "name",
 *      title="Nom responsable",
 *      description="Nom reponsable del cicle",
 *      example="Juan Segura",
 *      type="string"),
 *     @OA\Property(
 *      property = "cCiclo",
 *      title="Cicle en castellà",
 *      description="Cicle en castellà",
 *      example="Atención a personas en situación de dependencia",
 *      type="string"),
 *     @OA\Property(
 *      property = "vCiclo",
 *      title="Cicle en valencià",
 *      description="Cicle en valencià",
 *      example="Atenció a persones en situació de dependència",
 *      type="string"),
 *     )
 * )
 */
class CicloResource extends JsonResource
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
            'codigo' => $this->codigo,
            'ciclo' => $this->ciclo,
            'Dept' => $this->Dept,
            'cDept' => $this->cDept,
            'vDept' => $this->vDept,
            'responsable' => $this->responsable,
            'name' => $this->Responsable->fullName,
            'vCiclo' => $this->vCiclo,
            'cCiclo' => $this->cCiclo,
        ];
    }
}
