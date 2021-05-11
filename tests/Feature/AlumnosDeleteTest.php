<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class AlumnosDeleteTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos/3';
    const WRONG_PETITION = 'api/alumnos/40';
    const METHOD = 'DELETE';


    public function testSuccesfulDelete(){

        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 3,
      ]]);
    }

    public function testErrorDelete(){

        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::WRONG_PETITION)
            ->assertStatus(401)
            ->assertJson([
                "message" => "No query results for model [App\\Models\\Alumno] 40"]);
    }

    public function testSelfUserSuccesful()
    {
        $this->seed();
        $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 3,
                ]]);
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
