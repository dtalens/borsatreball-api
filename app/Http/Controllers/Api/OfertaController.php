<?php

namespace App\Http\Controllers\Api;

use App\Models\Oferta;
use App\Http\Resources\OfertaResource;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Ciclo;
use App\Models\User;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Get(
 * path="/api/ofertas",
 * summary="Dades de les ofertes",
 * description="Torna les dades de les ofertes segons usuari",
 * operationId="indexAlumnes",
 * tags={"ofertas"},
 * security={ {"apiAuth": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Ofertes segons el permis",
 *    @OA\JsonContent(
 *        @OA\Property(
 *          property="data",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/OfertaResource")
 *        )
 *    )
 *   )
 * )
 */

class OfertaController extends ApiBaseController
{
    use traitRelation;

    public function model(){
        return 'Oferta';
    }
    protected function relationShip()
    {
        return 'ciclos';
    }

    public function index()
    {
        return $this->filterIndex(false);
    }

    public function indexArxiu()
    {
        return $this->filterIndex(true);
    }

    private function filterIndex($archivada){
        if ($archivada && (AuthUser()->isEmpresa() || AuthUser()->isAlumno())) return [];
        if (AuthUser()->isEmpresa()) return OfertaResource::collection(Oferta::BelongsToEnterprise(AuthUser()->id)->where('archivada',$archivada)->orderBy('updated_at','DESC')->get());
        if (AuthUser()->isAlumno()){
            $ofertasTrabajo = Oferta::BelongsToCicles(Alumno::find(AuthUser()->id)->ciclosAcabados)->where('validada',true)->where('activa',true)->where('estudiando',false)->where('archivada',false);
            $ofertas = $ofertasTrabajo->concat(Oferta::BelongsToCicles(Alumno::find(AuthUser()->id)->ciclosValidos)->where('validada',true)->where('activa',true)->where('estudiando',true)->where('archivada',false));
            return OfertaResource::collection($ofertas);

        }
        if (AuthUser()->isResponsable()) return OfertaResource::collection(Oferta::BelongsToCicles(Ciclo::where('responsable',AuthUser()->id)->get())->where('archivada',$archivada));

        return OfertaResource::collection(Oferta::where('archivada',$archivada)->get());
    }


    public function alumnoInterested(Request $request,$id)
    {
        Oferta::find($id)->alumnos()->sync([AuthUser()->id =>['interesado'=>$request->interesado]]);
        return response($this->show($id),200);
    }

    public function destroy($id)
    {
        $oferta = Oferta::find($id);
        $oferta->archivada = 1;

        if ($oferta->save()) return response(1,200);

        return response("No he pogut Esborrar $id",400);
    }

    protected function adviseSomeOne($oferta)
    {
        foreach ($oferta->Ciclos as $ciclo){
            if (! $oferta->archivada)
                $ciclo->Responsable->notify(new ValidateOffer($oferta->id));
        }
    }

    public function valida(Request $request,$id)
    {
        $oferta = Oferta::find($id);
        $oferta->validada = $request->validada;
        $oferta->save();
        foreach ($this->lookStudents($oferta) as $alumno){
            User::find($alumno->id_alumno)->notify(new ValidateOffer($oferta->id));
        }

        return new $this->resource($oferta);
    }

    private function lookStudents($oferta){
        $ciclos = hazArray($oferta->Ciclos,'id','id');

        if (!$oferta->estudiando){
            $any = $oferta->any?$oferta->any:0;

            return DB::table('alumnos_ciclos')
                ->select('id_alumno')
                ->distinct()
                ->whereIn('id_ciclo',$ciclos)
                ->where('validado',1)
                ->where('any','>=',$any)
                ->get();
        }
        return DB::table('alumnos_ciclos')
            ->select('id_alumno')
            ->distinct()
            ->whereIn('id_ciclo',$ciclos)
            ->where('validado',1)
            ->get();
    }

}

