<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *     title="Oferta",
 *     description="Ofertas",
 *     @OA\Xml(name="OfertasResource"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador de oferta",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "id_empresa",
 *      title="id_empresa",
 *      description="ID de l'empresa que fa l'oferta",
 *      example="5",
 *      type="integer"),
 *     @OA\Property(
 *      property = "descripcion",
 *      title="descripcion",
 *      description="Descripció de l'oferta",
 *      example="Se busca persona capaz de aguantar horas de sol",
 *      type="string"),
 *     @OA\Property(
 *      property = "puesto",
 *      title="puesto",
 *      description="Lloc de treball",
 *      example="Peon caminero",
 *      type="string"),
 *     ),
 *     @OA\Property(
 *      property = "tipo_contrato",
 *      title="tipo_contrato",
 *      description="Tipo de contrato ofertado",
 *      example="Temporal",
 *      type="string"),
 *     ),
 *     @OA\Property(
 *      property = "activa",
 *      title="activa",
 *      description="Oferta Activa",
 *      example="1",
 *      type="boolean"),
 *     ),
 *     @OA\Property(
 *      property = "contacto",
 *      title="contacto",
 *      description="Persona de contacto",
 *      example="Pepe Botera",
 *      type="string"),
 *     ),
 *     @OA\Property(
 *      property = "telefono",
 *      title="telefono",
 *      description="Telefono de contacto",
 *      example="546654456",
 *      type="string"),
 *     ),
 *      @OA\Property(
 *      property = "email",
 *      title="email",
 *      description="Email de contacto",
 *      example="PepeBotera@gmail.com",
 *      type="string"),
 *     ),
 *      @OA\Property(
 *      property = "mostrar_contacto",
 *      title="mostrar_contacto",
 *      description="Muestro o no el contacto",
 *      example="1",
 *      type="boolean"),
 *     ),
 *      @OA\Property(
 *      property = "validada",
 *      title="validada",
 *      description="Oferta Validada",
 *      example="1",
 *      type="boolean"),
 *     ),
 *      @OA\Property(
 *      property = "estudiando",
 *      title="estudiando",
 *      description="Acepta estudiantes",
 *      example="0",
 *      type="boolean"),
 *     ),
 *      @OA\Property(
 *      property = "interesado",
 *      title="interesado",
 *      description="Si es alumno, si esta interesado en la oferta",
 *      example="0",
 *      type="boolean"),
 *      ,
 *      @OA\Property(
 *      property = "alumnos",
 *      title="alumnos",
 *      description="Si no es alumno, datos alumnos interesado",
 *      type="array",
 *      @OA\Items(ref="#/components/schemas/AlumnoResource")
 *      ),
 *      @OA\Property(
 *      property = "ciclos",
 *      title = "ciclos requeridos",
 *      type="array",
 *      @OA\Items(
 *         @OA\Property(
 *          property = "id",
 *          title="id",
 *          description="Identificador de ciclo",
*           example="2",
 *          type="integer") ,
 *      )
 *      ),
 *      @OA\Property(
 *      property = "empresa",
 *      title="empresa",
 *      description="Empresa Oferta",
 *      example="Pepe Botera y Otilio,S.A",
 *      type="string"),
 *      ),
 *      @OA\Property(
 *      property = "created_at",
 *      title="created_at",
 *      description="Hora de creació registre",
 *      example="2022-03-17 19:37:34",
 *      type="time"),
 *      @OA\Property(
 *      property = "updated_at",
 *      title="updated_at",
 *      description="Hora de modificació registre",
 *      example="2022-03-17 19:37:34",
 *      type="time")
 * )
 */


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
        return $this->alumnos->where('id',AuthUser()->id)->count();

    }





}
