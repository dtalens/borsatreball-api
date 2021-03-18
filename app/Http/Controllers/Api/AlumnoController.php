<?php

namespace App\Http\Controllers\Api;

use App\Entities\Ciclo;
use App\Http\Requests\AlumnoStoreRequest;
use App\Http\Resources\AlumnoResource;

use Illuminate\Http\Request;

use App\Entities\Alumno;
use App\Notifications\ValidateStudent;

use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use function PHPUnit\Framework\throwException;


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

    protected function validaCiclo(Request $request,$idAlumno,$idCiclo)
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
            return parent::index();
        }
        throw new AuthenticationException('Usuario no autenticado');
    }

    public function show($id)
    {
        if (AuthUser()->isAlumno() && AuthUser()->id == $id){
            return parent::show($id);
        } else {
            throw new UnauthorizedException('No tens permisos');
        }
    }

    public function update(AlumnoStoreRequest $request, $id)
    {
        if (AuthUser()->isAlumno() && AuthUser()->id == $id){
            $registro = Alumno::findOrFail($id);
            return $this->manageResponse($registro->update($request->all()));
        }
        else {
            throw new UnauthorizedException('No tens permisos');
        }
    }

    public function store(AlumnoStoreRequest $request)
    {
        return $this->manageResponse(Alumno::create($request->all()));
    }



}


