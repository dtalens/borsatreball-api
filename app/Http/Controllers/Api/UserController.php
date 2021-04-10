<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;


/**
* @OA\Get(
 * path="/api/users/{email}/available",
 * summary="Mira si el email està disponible",
 * description="Mira si el email està disponible",
 * operationId="showDisponible",
 * tags={"users"},
 * @OA\Parameter(
 *          name="email",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Boolean",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          title="available",
 *          description="Esta el email disponible",
 *          example=false,
 *          type="boolean"
 *       )
 *    )
 *   )
 * )
 */



class UserController extends ApiBaseController
{

    public function model(){
        return 'User';
    }

    public function index()
    {
        if (!AuthUser()) throw new AuthenticationException('No autenticado');
        if (AuthUser()->isAdmin()) return parent::index();
        if (AuthUser()) return parent::show(AuthUser()->id);
    }

    public function show($id)
    {
        if (AuthUser()->isAdmin()) return parent::show($id);
        if (AuthUser()) return parent::show(AuthUser()->id);

        throw new AuthenticationException('No autenticado');
    }

    public function update(Request $request, $id)
    {
        if (AuthUser()->isAlumno()) $id = AuthUser()->id;

        return parent::update($request,$id);
    }

    public function isEmailAvailable($email){
        return response()->json([
            'data' => !User::where('email',$email)->count()
        ], 200);
    }

}
