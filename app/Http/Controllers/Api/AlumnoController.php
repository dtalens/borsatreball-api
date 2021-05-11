<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AlumnoCicloUpdateRequest;
use App\Models\Ciclo;
use Illuminate\Http\Request;
use App\Http\Resources\AlumnoResource;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Notifications\ValidateStudent;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;

/**
 * @OA\Get(
 * path="/api/alumnos",
 * summary="Dades dels alumnes",
 * description="Torna les dades dels alumnes amb els cicles",
 * operationId="indexAlumnes",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Alumnes segons el permis",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/AlumnoResource")
 *        )
 *    )
 *   )
 * )
 */

/**
 * @OA\Post(
 * path="/api/alumnos",
 * summary="Guardar alumno",
 * description="Guardar perfil alumno",
 * operationId="storeAlumnes",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoStoreRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Alumno correctament donat d'alta",
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoResource")
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
 * path="/api/alumnos/{id}",
 * summary="Modificar alumno",
 * description="Modificar perfil alumno",
 * operationId="updateAlumnes",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoStoreRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Alumno correctament donat d'alta",
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoResource")
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
 * path="/api/alumnos/{alumno}/ciclo/{id}",
 * summary="Validar ciclo alumne",
 * description="Modificar cicle alumne",
 * operationId="updateAlumnesCiclo",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="alumno",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoCicloUpdateRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Alumno amb cicle",
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoResource")
 * ),
 * @OA\Response(
 *    response=405,
 *    description="Forbidden",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Forbidden.")
 *        )
 *     )
 * )
 */

/**
 * @OA\Get(
 * path="/api/alumnos/{id}",
 * summary="Dades d'un alumne",
 * description="Torna les dades d'un alumne amb els cicles",
 * operationId="showAlumnes",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Alumne segons el permis",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/AlumnoResource")
 *        )
 *    )
 *   )
 * )
 */


/**
 * @OA\Delete (
 * path="/api/alumnos/{id}",
 * summary="Esborra Alumne",
 * description="Torna les dades de l'alumne esborrat",
 * operationId="deleteAlumnes",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Alumne esborrat",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/AlumnoResource")
 *        )
 *    )
 *   )
 * )
 */

class AlumnoController extends ApiBaseController
{

    public function model(){
        return 'Alumno';
    }


    protected function validaCiclo(AlumnoCicloUpdateRequest $request,$idAlumno,$idCiclo)
    {
        if (AuthUser()->isResponsable()||AuthUser()->isAdmin()) {
            $alumno = Alumno::find($idAlumno);
            $any = $request->any??0;
            $alumno->Ciclos()->updateExistingPivot($idCiclo, ['any' => $request->any, 'validado' => $request->validado]);
            return  new AlumnoResource($alumno);
        } else {
            throw new UnauthorizedException('Forbidden.');
        }
    }

    protected function adviseSomeOne($registro){
        foreach ($registro->ciclosNoValidos as $ciclo){
            $ciclo->Responsable->notify(new ValidateStudent($ciclo));
        }
    }


    public function index()
    {
        if (AuthUser()->isResponsable()) {
            return AlumnoResource::collection(Alumno::BelongsToCicles(Ciclo::where('responsable', AuthUser()->id)->get()));
        }
        if (AuthUser()->isAlumno()) {
            return AlumnoResource::collection(Alumno::where('id',AuthUser()->id)->get());
        }
        if (AuthUser()->isEmpresa()) {
            return AlumnoResource::collection(Alumno::InterestedIn(AuthUser()->id));
        }
        if (AuthUser()->isAdmin()) {
            return AlumnoResource::collection(Alumno::all());
        }
        throw new AuthenticationException('Usuario no autenticado');
    }

    public function show($id)
    {
        if (AuthUser()->id == $id){
            return parent::show($id);
        } else {
            throw new UnauthorizedException('Forbidden.');
        }
    }

    public function update(Request $request, $id)
    {
        if (AuthUser()->id == $id){
            $alumno = Alumno::findOrFail($id);
            $alumno->update($request->except(['id']));
            $alumno->Ciclos()->sync($request->ciclos);
            return new AlumnoResource($alumno);
        }
        else {
            throw new UnauthorizedException('Forbidden.');
        }
    }

    public function destroy($id)
    {
        if ($this->authAlumno($id)){
            Alumno::findOrFail($id);
            $result = DB::transaction(function () use ($id) {
                if (Alumno::destroy($id)) {
                    return User::destroy($id);
                }
                return false;
            });
            return $result?response(['data'=>['id'=>$id]],200):response(['message'=>'Error'],401);
        } else {
            throw new UnauthorizedException('Forbidden.');
        }

    }

    private function authAlumno($id){
        if (AuthUser()->id == $id) {
            return true;
        }
        if (AuthUser()->isAdmin() || AuthUser()->isResponsable()) {
            return true;
        }
        return false;
    }

}


