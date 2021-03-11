<?php

namespace App\Http\Controllers\Api;

use App\Entities\Oferta;
use App\Http\Resources\OfertaResource;
use Illuminate\Http\Request;
use App\Entities\Alumno;
use App\Entities\Ciclo;
use App\Entities\User;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\DB;

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
            $ofertasFinalitzat = OfertaResource::collection(Oferta::BelongsToCicles(Alumno::find(AuthUser()->id)->ciclos->where('pivot.validado','=',true)->where('pivot.any', '!=', null))
                ->where('validada',true)->where('activa',true)->where('estudiando',false)->where('archivada',false));
            $ofertas = $ofertasFinalitzat->concat(OfertaResource::collection(Oferta::BelongsToCicles(Alumno::find(AuthUser()->id)->ciclos->where('pivot.validado','=',true))
                ->where('validada',true)->where('activa',true)->where('estudiando',true)->where('archivada',false)));
            return $ofertas->values();// values devuelve un array en vez de un objeto
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

