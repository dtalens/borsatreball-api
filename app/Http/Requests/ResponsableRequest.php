<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     title="responsable",
 *     description="Fillable responsables",
 *     @OA\Xml(name="responsable"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador d'usuari",
 *      example="2",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "nombre",
 *      title="nombre",
 *      description="Nom del responsable",
 *      example="Jose Manuel",
 *      type="string"),
 *     @OA\Property(
 *      property = "apellidos",
 *      title="apelllidos",
 *      description="Cognoms del responsable",
 *      example="Miró García",
 *      type="string"),
 *
 *    )
 */

class ResponsableRequest extends FormRequest
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
            'nombre'=> 'required|max:25',
            'apellidos' => 'required|max:50'
            ];
    }
}
