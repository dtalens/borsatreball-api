<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class UserController extends ApiBaseController
{

    public function model(){
        return 'User';
    }

    public function index()
    {
        if (AuthUser()->isAdmin()) return parent::index();
        if (AuthUser()) return parent::show(AuthUser()->id);

        return response('No autenticado', 405);
    }

    public function show($id)
    {
        if (AuthUser()->isAdmin()) return parent::show($id);
        if (AuthUser()) return parent::show(AuthUser()->id);

        return response('No autenticado', 405);
    }

    public function update(Request $request, $id)
    {
        if (AuthUser()->isAlumno()) $id = AuthUser()->id;

        return parent::update($request,$id);
    }

}
