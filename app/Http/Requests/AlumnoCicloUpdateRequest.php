<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     title="AlumneCicle",
 *     description="Fillable AlumneCicle",
 *     @OA\Xml(name="AlumnoCiclo"),
 *     @OA\Property(
 *      property = "any",
 *      title="any",
 *      description="Any obtenció cicle",
 *      example="2018",
 *      type="integer"),
 *     @OA\Property(
 *      property = "validado",
 *      title="validado",
 *      description="El cicle ha estat validad",
 *      example="1",
 *      type="boolean")
 *    )
 */

class AlumnoCicloUpdateRequest extends FormRequest
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
            'validado'=> 'required|boolean',
            'any'=> 'required_if:validado,true|exclude_if:validado,false|numeric|min:2000|max:'.(date('Y')),
            ];
    }
}
