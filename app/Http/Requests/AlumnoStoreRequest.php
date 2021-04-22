<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema(
 *     title="Alumne",
 *     description="Fillable Alumnos",
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
 *      property = "ciclos",
 *      title="cicles",
 *      description="Cicles de l'alumne",
 *      type="array",
 *      example = "[2,4]",
 *     @OA\Items(),
 *      ) ,
 *    )
 */

class AlumnoStoreRequest extends FormRequest
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
            'nombre'=> 'required',
            'apellidos' => 'required'
            ];
    }
}
