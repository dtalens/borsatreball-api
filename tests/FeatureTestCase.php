<?php


namespace Tests;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    const ADMIN_ROL = 2;
    const RESPONSABLE_ROL = 3;
    const EMPRESA_ROL = 5;
    const ALUMNO_ROL = 7;
    const FAKE_ROL = 37;
    const ID_EMPRESA_WITH_OFFERS = 5;
    const ID_EMPRESA_WITHOUT_OFFERS = 6;
    const ALUMNO_FIELDS = ['id','nombre','apellidos','domicilio','info','bolsa','cv_enlace','telefono','email','ciclos','created_at','updated_at'];
    const CICLOS_FIELDS = ['id','codigo','ciclo','Dept','cDept','vDept','responsable','vCiclo','cCiclo'];
    const EMPRESA_FIELDS = ['id','cif','nombre','domicilio','localidad','contacto','telefono','email','web','descripcion','created_at','updated_at'];
    const OFERTA_FIELDS = ['id','id_empresa','descripcion','puesto','tipo_contrato','activa','contacto','telefono','email','mostrar_contacto','validada','estudiando','archivada','ciclos','empresa','alumnos','created_at','updated_at'];
    const OFERTA_FIELDS_ALUMNO = ['id','id_empresa','descripcion','puesto','tipo_contrato','activa','contacto','telefono','email','mostrar_contacto','validada','estudiando','archivada','ciclos','empresa','interesado','created_at','updated_at'];


    protected function actingAsRol($rol){
        $user =  User::where('rol',$rol)->first();
        Passport::actingAs(
            $user,
            []);
        return $user;
    }

    protected function actingAsUser($id_user){
        $user =  User::find($id_user);
        Passport::actingAs(
            $user,
            []);
        return $user;
    }
    protected function getDataFromJson($method,$route,$data=[]){
        return $this->json($method, $route,$data,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(["data" => []])
            ->decodeResponseJson()
            ->json()['data'];
    }
    protected function expectedForbidden($method,$route,$data=[]){
        $this->json($method, $route,$data,['Accept' => 'application/json'])
            ->assertStatus(405)
            ->assertJson([
                "message" => "Forbidden.",
            ]);

    }
    protected function expectedUnauthenticated($method,$route,$data=[]){
        $this->json($method, $route,$data,['Accept' => 'application/json'])
            ->assertStatus(421)
            ->assertJson([
                "message" => "Unauthenticated.",
            ]);
    }
    protected function expectedNotFound($method,$route,$modelo,$data=[]){
        $this->json($method, $route,$data,['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "$modelo not found.",
            ]);
    }

}
