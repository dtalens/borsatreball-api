<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class CiclosUpdateTest extends FeatureTestCase
{

    const PETITION = 'api/ciclos/2';
    const WRONG_PETITION = 'api/ciclos/1';
    const CICLO_DATA = [
        "codigo" => "CFMX",
        "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
        "Dept" => "Img",
        "cDept" => "Imagen Personal",
        "vDept" => "Imatge Personal",
        "responsable" => 2,
        "cCiclo" => "Atención a personas en situación de dependencia",
        "vCiclo"=> "Atenció a persones en situació de dependència"];
    const CICLO_DATA_WITH_ID = [
        "id" => 1,
        "codigo" => "CFMX",
        "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
        "Dept" => "Img",
        "cDept" => "Imagen Personal",
        "vDept" => "Imatge Personal",
        "responsable" => 2,
        "cCiclo" => "Atención a personas en situación de dependencia",
        "vCiclo"=> "Atenció a persones en situació de dependència"];
    const CICLO_DATA_INCOMPLETE = [
        "responsable" => 2,
        ];

    public function testUnAutenticatedFails()
    {
        $this->seed();
        $this->expectedUnauthenticated('PUT', self::PETITION,self::CICLO_DATA);
    }

    public function testSuccesfulUpdate(){
        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json('PUT', self::PETITION,self::CICLO_DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                        "id" => 2,
                        "codigo" => "CFMX",
                        "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
                        "Dept" => "Img",
                        "cDept" => "Imagen Personal",
                        "vDept" => "Imatge Personal",
                        "responsable" => 2,
                        "cCiclo" => "Atención a personas en situación de dependencia",
                        "vCiclo"=> "Atenció a persones en situació de dependència"]
            ]);
    }

    public function testFailIfCicloNotExistsUpdate(){
        $this->seed();
        $this->actingAsRol(SELF::ADMIN_ROL);
        $this->json('PUT', self::WRONG_PETITION,self::CICLO_DATA, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "No query results for model [App\\Models\\Ciclo] 1",
            ]);
    }

    public function testUpdateNotChangeId(){
        $this->seed();
        $this->actingAsRol(SELF::ADMIN_ROL);
        $this->json('PUT', self::PETITION,SELF::CICLO_DATA_WITH_ID, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 2,
                    "codigo" => "CFMX",
                    "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
                    "Dept" => "Img",
                    "cDept" => "Imagen Personal",
                    "vDept" => "Imatge Personal",
                    "responsable" => 2,
                    "cCiclo" => "Atención a personas en situación de dependencia",
                    "vCiclo"=> "Atenció a persones en situació de dependència"]
            ]);
    }

    public function testSuccesfulIncomplete()
    {
        $this->seed();
        $this->actingAsRol(SELF::ADMIN_ROL);
        $this->json('PUT', self::PETITION,SELF::CICLO_DATA_WITH_ID, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 2,
                    "codigo" => "CFMX",
                    "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
                    "Dept" => "Img",
                    "cDept" => "Imagen Personal",
                    "vDept" => "Imatge Personal",
                    "responsable" => 2,
                    "cCiclo" => "Atención a personas en situación de dependencia",
                    "vCiclo"=> "Atenció a persones en situació de dependència"]
            ]);
    }


}
