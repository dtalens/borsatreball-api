<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class ResponsableDeleteTest extends FeatureTestCase
{
    const PETITION = 'api/responsables/2';
    const WRONG_PETITION = 'api/responsables/40';
    const METHOD = 'DELETE';


    public function testSuccesfulDelete(){

        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 2,
      ]]);
    }

    public function testErrorDelete(){

        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::WRONG_PETITION)
            ->assertStatus(401)
            ->assertJson([
                "message" => "No query results for model [App\\Models\\Responsable] 40"]);
    }

    public function testSelfUserFails()
    {
        $this->seed();
        $this->actingAsUser(2);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

    public function testUnauthorizeAlumnoFails()
    {
        $this->seed();
        $this->actingAsUser(4);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

    public function testUnauthorizeEmpresaFails()
    {
        $this->seed();
        $this->actingAsUser(4);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

}
