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
 *      property = "cDept",
 *      title="Departament Castellà",
 *      description="Nom en castellà del departament",
 *      example="Informática",
 *      type="string"),
 *     @OA\Property(
 *      property = "vDept",
 *      title="Departament Valencià",
 *      description="Nom en valencià del departament",
 *      example="Informàtica",
 *      type="string"),
 *     @OA\Property(
 *      property = "vCiclo",
 *      title="vCiclo",
 *      description="Informaciò del cicle",
 *      example="Diseeny d'aplicacion web",
 *      type="string"),
 *     @OA\Property(
 *      property = "cCiclo",
 *      title="cCiclo",
 *      description="Información del ciclo",
 *      example="Diseño de aplicaciones web",
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
            'responsable' => 'exists:users,id',
            'Dept' => 'required|max:3',
            'cDept' => 'required|max:50',
            'vDept' => 'required|max:50',
            'vCiclo' => 'string|max:80',
            'cCiclo' => 'string|max:80'
        ];
    }
}
