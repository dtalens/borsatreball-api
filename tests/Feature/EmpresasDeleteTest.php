<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class EmpresasDeleteTest extends FeatureTestCase
{
    const PETITION = 'api/empresas/7';
    const PETITION_WITH_OFFER = 'api/empresas/5';
    const WRONG_PETITION = 'api/empresas/40';
    const METHOD = 'DELETE';





    public function testSuccesfulDelete(){

        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 7,
      ]]);
    }

    public function testWrongPetitionDelete(){

        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::WRONG_PETITION)
            ->assertStatus(401)
            ->assertJson([
                "message" => "No query results for model [App\\Models\\Empresa] 40"]);
    }

    public function testDeleteWithOfferPetitionDeleteFails(){

        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION_WITH_OFFER)
            ->assertStatus(401)
            ->assertJson([
                "message" => "L'empresa té ofertes. Esborra-les primer"]);
    }

    public function testSelfUserSuccesful()
    {
        $this->seed();
        $this->actingAsUser(7);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 7,
                ]]);
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
