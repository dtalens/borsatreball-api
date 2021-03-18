<?php
/**
 * Created by PhpStorm.
 * User: igomis
 * Date: 2019-03-24
 * Time: 09:56
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

trait traitRelation
{
    /*
    protected function manageResponse($registro, Request $request=null)
    {
        $relation = $this->relationShip();
        $registro->$relation()->sync($request->$relation);
        $this->adviseSomeOne($registro);

        return parent::manageResponse($registro, $request);
    }*/

    //abstract protected function relationShip();
    //abstract protected function adviseSomeOne($registro);

}
