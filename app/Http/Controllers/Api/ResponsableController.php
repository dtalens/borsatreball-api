<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AuthController;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\ResponsableResource;
use App\Models\Responsable;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @OA\Get(
 * path="/api/responsables",
 * summary="Dades dels responsables",
 * description="Torna les dades dels responsables amb els cicles",
 * operationId="indexResponsables",
 * tags={"responsables"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Responsables segons el permis",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ResponsableResource")
 *        )
 *    )
 *   )
 * )
 */

/**
 * @OA\Post(
 * path="/api/responsables",
 * summary="Guardar responsable",
 * description="Guardar perfil responsable",
 * operationId="storeResponsables",
 * tags={"responsables"},
 * security={ {"apiAuth": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/ResponsableRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Responsable correctament donat d'alta",
 *    @OA\JsonContent(ref="#/components/schemas/ResponsableResource")
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
 * path="/api/responsables/{id}",
 * summary="Modificar responsable",
 * description="Modificar perfil responsable",
 * operationId="updateResponsables",
 * tags={"responsables"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/ResponsableRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Responsable correctament donat d'alta",
 *    @OA\JsonContent(ref="#/components/schemas/ResponsableResource")
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
 * path="/api/responsables/{id}",
 * summary="Dades d'un responsable",
 * description="Torna les dades d'un responsable amb els cicles",
 * operationId="showResponsables",
 * tags={"responsables"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Responsable",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ResponsableResource")
 *        )
 *    )
 *   )
 * )
 */


/**
 * @OA\Delete (
 * path="/api/reponsables/{id}",
 * summary="Esborra Responsable",
 * description="Torna les dades del responsable esborrat",
 * operationId="deleteResponsables",
 * tags={"responsables"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Responsable esborrat",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/ResponsableResource")
 *        )
 *    )
 *   )
 * )
 */

class ResponsableController extends ApiBaseController
{

    public function model(){
        return 'Responsable';
    }

    public function index()
    {
        return ResponsableResource::collection(Responsable::all());
    }

    public function store(UserStoreRequest $request){
        $authController = new AuthController();
        return $authController->signup($request);
    }

    public function update(Request $request, $id)
    {
        if (onlySelfAuth($id) || AuthUser()->isAdmin()) {
            if ($responsable = Responsable::find($id)){
                $responsable->update($request->except(['id']));
                return new ResponsableResource($responsable);
            } else {
                throw new NotFoundHttpException("Responsable not found.");
            }
        } else {
            throw new UnauthorizedException('Forbidden.');
        }
    }

    public function destroy($id)
    {
        Responsable::findOrFail($id);
        $result = DB::transaction(function () use ($id) {
            if (Responsable::destroy($id)) {
                return User::destroy($id);
            }
            return false;
        });
        return $result?response(['data'=>['id'=>$id]],200):response(['message'=>'Error'],401);
    }



}


