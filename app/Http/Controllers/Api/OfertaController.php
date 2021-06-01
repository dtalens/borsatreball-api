<?php

namespace App\Http\Controllers\Api;

use App\Models\Oferta;
use App\Http\Resources\OfertaResource;
use App\Notifications\OfferStudent;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Ciclo;
use App\Models\User;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Get(
 * path="/api/ofertas",
 * summary="Dades de les ofertes",
 * description="Torna les dades de les ofertes segons usuari",
 * operationId="indexOfertas",
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

/**
 * @OA\Get(
 * path="/api/ofertas-arxiu",
 * summary="Dades de les ofertes",
 * description="Torna les dades de les ofertes segons usuari",
 * operationId="indexOfertasArchivadas",
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

/**
 * @OA\Get(
 * path="/api/ofertas/{id}",
 * summary="Dades d'una oferta",
 * description="Torna les dades d'una oferta amb dades agregades",
 * operationId="showOfertas",
 * tags={"ofertas"},
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
 *          @OA\Items(ref="#/components/schemas/OfertaResource")
 *        )
 *    )
 *   )
 * )
 */

/**
 * @OA\Put(
 * path="/api/ofertas/{id}/validar",
 * summary="Valida Oferta",
 * description="Torna les dades de l'oferta valida/desvalidada",
 * operationId="validaOfertas",
 * tags={"ofertas"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(
 *     @OA\Property(
 *      property = "validada",
 *      title="validada",
 *      description="Oferta Validada",
 *      example="1",
 *      type="boolean"),
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Oferta",
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

/**
 * @OA\Put(
 * path="/api/ofertas/{id}/alumno",
 * summary="Muestra interes por la oferta",
 * description="Torna les dades de l'oferta",
 * operationId="InteresOfertas",
 * tags={"ofertas"},
 * security={ {"apiAuth": {} }},
 * @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 * ),
 * @OA\RequestBody(
 *    required=true,
 *    @OA\JsonContent(
 *     @OA\Property(
 *      property = "interesado",
 *      title="interesado",
 *      description="Interes",
 *      example="1",
 *      type="boolean"),
 *     )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Oferta",
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

    public function model(){
        return 'Oferta';
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
        if ($archivada && (AuthUser()->isEmpresa() || AuthUser()->isAlumno())) return response(['data'=>[]],200);
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
        if (AuthUser()->isAlumno()) {
            $oferta = Oferta::find($id);
            if ($oferta->validada && !$oferta->archivada) {
                if ($request->interesado) {
                    $oferta->alumnos()->syncWithoutDetaching([AuthUser()->id]);
                    $oferta->Empresa->User->notify(new OfferStudent($oferta));
                } else {
                    $oferta->alumnos()->detach([AuthUser()->id]);
                }
                return new OfertaResource($oferta);
            } else {
                throw new NotFoundHttpException('Oferta No valida');
            }
        } else {
            throw new UnauthorizedException('Forbidden.');
        }
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
        if ($oferta->validada) {
            foreach ($this->lookStudents($oferta) as $alumno) {
                User::find($alumno->id_alumno)->notify(new ValidateOffer($oferta->id));
            }
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

