<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Empresa",
 *     description="Fillable Empresa",
 *     @OA\Xml(name="Empresa"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador d'empresa",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "nombre",
 *      title="nombre",
 *      description="Nom de l'alumne",
 *      example="Jose Manuel",
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
 *      description="Telèfon de l'alumne",
 *      example="666666666",
 *      type="string"),
 *     @OA\Property(
 *      property = "cif",
 *      title="cif",
 *      description="Cif de l'empresa",
 *      example="A12345678",
 *      type="string"),
 *     @OA\Property(
 *      property = "localidad",
 *      title="localidad",
 *      description="Localidad de l'empresa",
 *      example="Alcoi",
 *      type="string"),
 *     @OA\Property(
 *      property = "contacto",
 *      title="contacto",
 *      description="Persona de contacte de l'empresa",
 *      example="Pepe Botera",
 *      type="string"),
 *     @OA\Property(
 *      property = "web",
 *      title="web",
 *      description="Pagina web de l'empresa",
 *      example="http://www.pepebotera.com",
 *      type="string"),
 *     @OA\Property(
 *      property = "descripcion",
 *      title="descripcion",
 *      description="Descripció de l'empresa",
 *      example="Chapuzas a domicilio",
 *      type="string"))
 */
class EmpresaStoreRequest extends FormRequest
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
        return ['cif' => 'required', 'nombre'   => 'required',
            'domicilio' => 'required',
            'telefono'    => 'required',
            'localidad'=> 'required',
            'contacto' => 'required'
        ];
    }
}
