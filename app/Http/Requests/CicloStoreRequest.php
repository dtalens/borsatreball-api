<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Cicle",
 *     description="Fillable Cicles",
 *     @OA\Xml(name="Ciclo"),
 *     @OA\Property(
 *      property = "codigo",
 *      title="codigo",
 *      description="Identificador de ciclo",
 *      example="DAW",
 *      type="string") ,
 *     @OA\Property(
 *      property = "ciclo",
 *      title="ciclo",
 *      description="Nom llarg del cicle",
 *      example="Disseny d'aplicacions web(LOE)",
 *      type="string"),
 *     @OA\Property(
 *      property = "Dept",
 *      title="departament",
 *      description="Departament del cicle",
 *      example="inf",
 *      type="string"),
 *     @OA\Property(
 *      property = "responsable",
 *      title="Responsable",
 *      description="Responsable del cicle",
 *      example="220",
 *      type="integer"),
 *     @OA\Property(
 *      property = "vCiclo",
 *      title="vCiclo",
 *      description="Info del cicle",
 *      example="Diseeny d'aplicacion web",
 *      type="string"),
 *    )
 */

class CicloStoreRequest extends FormRequest
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
            'codigo' => 'required|string|max:4',
            'ciclo' => 'required|string|max:50',
            'responsable' => 'exists|users:id',
            'vCiclo' => 'required|string|max:80'
        ];
    }
}
