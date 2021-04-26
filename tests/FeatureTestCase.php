<?php


namespace Tests;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    const ALUMNO_ROL = 7;
    const EMPRESA_ROL = 5;
    const ADMIN_ROL = 2;
    const RESPONSABLE_ROL = 3;
    const FAKE_ROL = 37;

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
