<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


abstract class ApiBaseController extends Controller
{
    protected $resource;
    protected $entity;
    protected $request;

    public function __construct(){
       $this->resource = 'App\Http\Resources\\'.$this->model().'Resource';
       $this->entity = 'App\Models\\'.$this->model();
    }
    public abstract function model();

    public function index(){
        return $this->resource::collection($this->entity::all());
    }

    public function show($id){
        if ($registre = $this->entity::find($id)) {
            return new $this->resource($registre);
        } else {
            throw new NotFoundHttpException($this->model()." not found.");
        }
    }

    public function destroy($id)
    {
        if ($this->entity::destroy($id)) return response(1,200);

        return response("No he pogut Esborrar $id",400);
    }

    protected function response(array $errors)
    {
        $transformed = [];

        foreach ($errors as $field => $message) {
            $transformed[] = [
                'field' => $field,
                'message' => $message[0]
            ];
        }

        return response()->json([
            'errors' => $transformed
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

}
