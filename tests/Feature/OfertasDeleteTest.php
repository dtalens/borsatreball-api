<?php

namespace Tests\Feature;

use App\Models\Oferta;
use Tests\FeatureTestCase;

class OfertasDeleteTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas/4';
    const WRONG_PETITION = 'api/ofertas/40';
    const METHOD = 'DELETE';

    public function testSuccesfulDelete(){

        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $id = $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "id_empresa",
                    "descripcion",
                    "puesto",
                    "tipo_contrato",
                    "activa" ]
            ])
            ->decodeResponseJson()
            ->json()['data']['id'];
        $oferta = Oferta::find($id);
        $this->assertEquals(1,$oferta->archivada);
    }

    public function testWrongPetitionDelete(){

        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::WRONG_PETITION)
            ->assertStatus(401)
            ->assertJson([
                "message" => "No query results for model [App\\Models\\Oferta] 40"]);
    }


    public function testSelfUserSuccesful()
    {
        $this->seed();
        $this->actingAsUser(6);
        $id = $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "id_empresa",
                    "descripcion",
                    "puesto",
                    "tipo_contrato",
                    "activa" ]
            ])
            ->decodeResponseJson()
            ->json()['data']['id'];
        $oferta = Oferta::find($id);
        $this->assertEquals(1,$oferta->archivada);
    }

    public function testUnauthorizeAlumnoFails()
    {
        $this->seed();
        $this->actingAsUser(3);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

    public function testUnauthorizeEmpresaFails()
    {
        $this->seed();
        $this->actingAsUser(5);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

}
