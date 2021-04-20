<?php


namespace Tests;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    const ALUMNO_ROL = 7;
    const EMPRESA_ROL = 5;
    const ADMIN_ROL = 2;
    const FAKE_ROL = 37;

    protected function actingAsRol($rol,$rutas){
        Passport::actingAs(
            User::where('rol',$rol)->first(),
            $rutas);
    }
    protected function getDataFromJson($method,$route){
        return $this->json($method, $route)
            ->assertStatus(200)
            ->assertJsonStructure(["data" => []])
            ->decodeResponseJson()
            ->json()['data'];
    }

}
