<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Get(
 * path="/api/menu",
 * summary="Menu de l'aplicatiu",
 * description="Torna les dades dels menu que li correspon a l'usuari",
 * operationId="indexMenu",
 * tags={"menu"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Menu de l'aplicatiu segons permis",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/MenuResource")
 *        )
 *    )
 *   ),
 *  @OA\Response(
 *    response=421,
 *    description="Unauthenticated",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated."),
 *     )
 *  )
 * )
 */


class MenuController extends ApiBaseController
{

    public function model(){
        return 'Menu';
    }

    public function index(){
        $rolUser = AuthUser()->rol;
        return $this->resource::collection($this->entity::whereRaw('rol % ? = 0',[$rolUser])->orderBy('order')->get());
    }

}
