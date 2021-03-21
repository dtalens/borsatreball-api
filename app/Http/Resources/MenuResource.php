<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Menu",
 *     description="Menu d'usuari",
 *     @OA\Xml(name="Menu"),
 *     @OA\Property(
 *      property = "id",
 *      title="id",
 *      description="Identificador de menu",
 *      example="6",
 *      type="integer") ,
 *     @OA\Property(
 *      property = "order",
 *      title="order",
 *      description="Ordre del menu",
 *      example="4",
 *      type="integer"),
 *     @OA\Property(
 *      property = "icon",
 *      title="icon",
 *      description="Icona del menu",
 *      example="account_box",
 *      type="string"),
 *     @OA\Property(
 *      property = "text",
 *      title="text",
 *      description="Texte del menu",
 *      example="Perfil",
 *      type="string"),
 *     @OA\Property(
 *      property = "path",
 *      title="Path",
 *      description="Path del menu",
 *      example="/perfil",
 *      type="string"),
 *     @OA\Property(
 *      property = "rol",
 *      title="rol",
 *      description="rol",
 *      example="210",
 *      type="integer"),
 *     @OA\Property(
 *      property = "parent",
 *      title="parent",
 *      description="Pare del menu",
 *      example="null",
 *      type="string") ,
 *     @OA\Property(
 *      property = "model",
 *      title="model",
 *      description="???",
 *      example="1",
 *      type="boolean"),
 *     @OA\Property(
 *      property = "active",
 *      title="active",
 *      description="Es Actiu?",
 *      example="1",
 *      type="boolean"),
 *     @OA\Property(
 *      property = "comments",
 *      type="string",
 *      example="null",
 *      ),
 *     @OA\Property(
 *      property = "icon_alt",
 *      title="icon_at",
 *      description="Icona alternativa",
 *      example="null",
 *      type="string"),
 * )
 */

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
