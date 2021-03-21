<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Get(
 * path="/api/menu",
 * summary="Menu de l'aplicatiu",
 * description="Torna les dades dels menu que li correspon a l'usuari",
 * operationId="indexMenu",
 * tags={"menu"},
 * @OA\Response(
 *    response=200,
 *    description="Menu de l'aplicatiu",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/MenuResource")
 *        )
 *    )
 *   )
 * )
 */


class MenuController extends ApiBaseController
{

    public function model(){
        return 'Menu';
    }

    public function index(){
        return $this->resource::collection($this->entity::orderBy('order')->get());
//        return AuthUser();
        if (isset(AuthUser()->id)) return $this->resource::collection($this->entity::where('rol','!=',9999)->orderBy('order')->get());
        //return $this->resource::collection->$this->entity::isRol(AuthUser()->rol)->orderBy('order')->get());
        return $this->resource::collection($this->entity::where('rol',9999)->orderBy('order')->get());
    }

}
