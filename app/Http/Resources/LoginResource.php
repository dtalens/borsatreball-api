<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     title="Login Resource",
 *     description="Resposta al Login correcte",
 *     @OA\Xml(name="LoginResource"),
 *     @OA\Property(
 *      property = "access_token",
 *      title="access_token",
 *      description="Token d'accès",
 *      example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2IiwianRpIjoiNGY4YjE5OTMzNjQxZjVjMDVjNmU1MGE3MGQxNmE5M2ZkYWZmNjkwZDI5M2Y3MTU2NzM5NTkwMDM5YWFhM2FiZDcw",
 *      type="string") ,
 *     @OA\Property(
 *      property = "rol",
 *      title="rol",
 *      description="Rol d'usuari",
 *      example="2",
 *      type="integer"),
 *     @OA\Property(
 *      property = "token_type",
 *      title="token_type",
 *      description="Tipus de token",
 *      example="Bearer",
 *      type="string"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="id d'usuari",
 *      example="12",
 *      type="integer"),
 *     @OA\Property(
 *      property = "name",
 *      title="name",
 *      description="Nom de l'usuari",
 *      example="Ignasi",
 *      type="string"),
 *     @OA\Property(
 *      property = "expires_at",
 *      title="expires_at",
 *      description="Hora caducitat token",
 *      example="2022-03-17 19:37:34",
 *      type="time")
 * )
 */


class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $tokenResult = $this->createToken('Token Acceso Personal');
        $token = $tokenResult->token;
        $token->save();

        return[
            'access_token' => $tokenResult->accessToken,
            'rol'          => $this->rol,
            'token_type'   => 'Bearer',
            'id'           => $this->id,
            'name'         => $this->name,
            'expires_at'   => Carbon::parse(
                $token->expires_at)
                ->toDateTimeString()];
    }
}
