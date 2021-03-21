<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AlumnoCicloUpdateRequest;
use App\Models\Ciclo;
use App\Http\Requests\AlumnoStoreRequest;
use App\Http\Resources\AlumnoResource;

use Illuminate\Http\Request;

use App\Models\Alumno;
use App\Notifications\ValidateStudent;

use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use function PHPUnit\Framework\throwException;


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
 * @OA\Put(
 * path="/api/alumnos/{idAlumno}/ciclo/{idCiclo}",
 * summary="Modificar ciclo Alumno",
 * description="Modificar ciclo Alumno",
 * operationId="updateAlumnesCicle",
 * tags={"alumnes"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="idAlumno",
 *          in="path",
 *          required=true,
 * ),
 * @OA\Parameter(
 *          name="idCiclo",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(ref="#/components/schemas/AlumnoCicloUpdateRequest")
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Alumno correctament modificat",
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

class AlumnoController extends ApiBaseController
{
    //use traitRelation;

    public function model(){
        return 'Alumno';
    }
   protected function relationShip()
    {
        return 'ciclos';
    }

    protected function validaCiclo(AlumnoCicloUpdateRequest $request,$idAlumno,$idCiclo)
    {
        $alumno = Alumno::find($idAlumno);
        $alumno->Ciclos()->updateExistingPivot($idCiclo, ['any' => $request->any,'validado'=>$request->validado]);
        return parent::manageResponse($alumno);
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
            return AlumnoResource::collection(Alumno::where('id', AuthUser()->id)->get());
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
            throw new UnauthorizedException('No tens permisos');
        }
    }

    public function update(AlumnoStoreRequest $request, $id)
    {
        if (AuthUser()->id == $id){
            $registro = Alumno::findOrFail($id);
            return $this->manageResponse($registro->update($request->all()));
        }
        else {
            throw new UnauthorizedException('No tens permisos:'.$id);
        }
    }

    public function store(AlumnoStoreRequest $request)
    {
        return $this->manageResponse(Alumno::create($request->all()));
    }



}


