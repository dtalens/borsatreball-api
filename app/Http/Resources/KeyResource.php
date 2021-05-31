<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Keys",
 *     description="Keys",
 *     @OA\Xml(name="KeysResource"),
 *     @OA\Property(
 *      property = "id",
 *      title="ID cicle",
 *      description="Identificador",
 *      example="2",
 *      type="integer")
 * )
 */
class KeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
        ];
    }
}
