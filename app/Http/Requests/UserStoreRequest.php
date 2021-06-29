<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="UserStoreRequest",
 *     description="Registrar Usuari",
 *     @OA\Xml(name="Register"),
 *     required = {"email,password,password_confirmation,rol,nombre,domicilio,telefono"},
 *     @OA\Property(
 *      property = "email",
 *      title="email",
 *      description="Email de l'usuari",
 *      example="pepebotera@gmail.com",
 *      type="email"),
 *     @OA\Property(
 *      property = "password",
 *      title="password",
 *      description="Password de l'usuari",
 *      example="1234",
 *      type="string"),
 *     @OA\Property(
 *      property = "password_confirmation",
 *      title="password",
 *      description="Password de l'usuari",
 *      example="1234",
 *      type="string"),
 *     @OA\Property(
 *      property = "rol",
 *      title="rol",
 *      description="Rol de l'usuari",
 *      example="2",
 *      type="integer"),
 *     @OA\Property(
 *      property = "nombre",
 *      title="nombre",
 *      description="Nom de l'alumne o empresa",
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
 *      type="string"),
 *     @OA\Property(
 *      property = "apellidos",
 *      title="apelllidos",
 *      description="Cognoms de l'alumne",
 *      example="Miró García",
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
 *      property = "ciclos",
 *      title="cicles",
 *      description="Cicles de l'alumne",
 *      type="array",
 *      example = "[2,4]",
 *     @OA\Items(),
 *      ) ,
 *
 *    )
 */


class UserStoreRequest extends FormRequest
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
                //camps soles de USER
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'rol'      => 'required',

            // camps comuns
            'nombre'   => 'required|max:25',
            // camps alumno i resposable
            'apellidos' => 'max:50|requiredIf:rol,'.config('role.alumno').','.config('role.responsable'),
            //camps alumno i empresa
            'domicilio' => 'requiredIf:rol,'.config('role.alumno').','.config('role.empresa'),
            'telefono'    => 'requiredIf:rol,'.config('role.alumno').','.config('role.empresa'),
                // camps soles empresa
            'cif'      => 'required_if:rol,'.config('role.empresa'),
            'localidad'=> 'required_if:rol,'.config('role.empresa'),
            'contacto' => 'required_if:rol,'.config('role.empresa'),
            'web'         => 'exclude_if:rol,'.config('role.alumno').','.config('role.responsable'),
            'descripcion' => 'exclude_if:rol,'.config('role.alumno').','.config('role.responsable'),

            // camps soles alumnop
            'info' => 'exclude_if:rol,'.config('role.empresa').','.config('role.responsable'),
            'bolsa' => 'exclude_if:rol,'.config('role.empresa').','.config('role.responsable'),
            'cv_enlace' => 'exclude_if:rol,'.config('role.empresa').','.config('role.responsable'),
            'ciclos' => 'requiredIf:rol,'.config('role.alumno')
        ];
    }
}
