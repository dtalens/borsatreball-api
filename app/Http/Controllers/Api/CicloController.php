<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\CicloStoreRequest;
use App\Models\Ciclo;

/**
 * @OA\Get(
 * path="/api/ciclos",
 * summary="Dades dels cicle",
 * description="Torna les dades dels cicles",
 * operationId="indexCicles",
 * tags={"cicles"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Cicles",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/CicloResource")
 *        )
 *    )
 *   )
 * )
 */


/**
 * @OA\Post(
 * path="/api/ciclos",
 * summary="Guardar ciclo",
 * description="Guardar ciclo",
 * operationId="storeCiclo",
 * tags={"cicles"},
 * security={ {"apiAuth": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/CicloStoreRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Cicle correctament donat d'alta",
 *    @OA\JsonContent(ref="#/components/schemas/CicloResource")
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="string", example="Credencials no vàlides")
 *        )
 *     )
 * )
 */

/**
 * @OA\Put(
 * path="/api/ciclos/{id}",
 * summary="Modificar cicle",
 * description="Modificar cicle",
 * operationId="updateCicles",
 * tags={"cicles"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/CicloStoreRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Ciclo correctament donat d'alta",
 *    @OA\JsonContent(ref="#/components/schemas/CicloResource")
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="string", example="Credencials no vàlides")
 *        )
 *     )
 * )
 */

/**
 * @OA\Get(
 * path="/api/ciclos/{id}",
 * summary="Dades d'un cicle",
 * description="Torna les dades d'un cicle",
 * operationId="showCicle",
 * tags={"cicles"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Ciclos",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/CicloResource")
 *        )
 *    )
 *   )
 * )
 */


class CicloController extends ApiBaseController
{
    public function model(){
        return 'Ciclo';
    }

    public function update(CicloStoreRequest $request, $id)
    {
        $registro = Ciclo::findOrFail($id);
        return $this->manageResponse($registro->update($request->all()));
    }
    public function store(CicloStoreRequest $request)
    {
        return $this->manageResponse(Ciclo::create($request));
    }

}
