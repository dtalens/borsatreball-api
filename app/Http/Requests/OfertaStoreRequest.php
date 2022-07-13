<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Oferta",
 *     description="Fillable Ofertas",
 *     @OA\Xml(name="Oferta"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador d'oferta",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "id_empresa",
 *      title="Empresa de la Oferta",
 *      description="Empresa de l'oferta",
 *      example="23",
 *      type="integer"),
 *     @OA\Property(
 *      property = "descripcion",
 *      title="descripcion",
 *      description="Descripció de l'oferta",
 *      example="Para arreglar carreteras comarcales",
 *      type="string"),
 *     @OA\Property(
 *      property = "puesto",
 *      title="puesto",
 *      description="Puesto de l'oferta",
 *      example="Peón Caminero",
 *      type="string"),
 *     @OA\Property(
 *      property = "tipo_contrato",
 *      title="Tipo de contrato",
 *      description="Tipo de contrato de la oferta",
 *      example="Indefinido",
 *      type="string"),
 *     @OA\Property(
 *      property = "activa",
 *      title="activa",
 *      description="Oferta activa",
 *      example="1",
 *      type="boolean"),
 *     @OA\Property(
 *      property = "contacto",
 *      title="contacto",
 *      description="Persona de contacte de l'empresa",
 *      example="Pepe Botera",
 *      type="string"),
 *     @OA\Property(
 *      property = "telefono",
 *      title="telefono",
 *      description="Telèfon de contacto",
 *      example="666666666",
 *      type="string"),
 *     @OA\Property(
 *      property = "email",
 *      title="email",
 *      description="email de contacte",
 *      example="pepebotera@gmail.com",
 *      type="string"),
 *     @OA\Property(
 *      property = "mostrar_contacto",
 *      title="Mostrar contacte",
 *      description="Mostre contacte de l'empresa",
 *      example="1",
 *      type="boolean"),
 *     @OA\Property(
 *      property = "validada",
 *      title="validada",
 *      description="Oferta Validada",
 *      example="1",
 *      type="boolean"),
 *      @OA\Property(
 *      property = "estudiando",
 *      title="estudiando",
 *      description="Acepta alumnos",
 *      example="1",
 *      type="boolean"),
 *      @OA\Property(
 *      property = "archivada",
 *      title="archivada",
 *      description="Oferta Archivada",
 *      example="0",
 *      type="boolean")
 * )
 */

class OfertaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_empresa' => 'required|exists:empresas,id',
        ];
    }
}
