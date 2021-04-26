<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class CiclosStoreTest extends FeatureTestCase
{

    const PETITION = 'api/ciclos';
    const CICLO_DATA = [
      "codigo" => "CFMX",
      "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
      "Dept" => "Img",
      "cDept" => "Imagen Personal",
      "vDept" => "Imatge Personal",
      "responsable" => 2,
      "cCiclo" => "Atención a personas en situación de dependencia",
      "vCiclo"=> "Atenció a persones en situació de dependència"];
    const ERROR_DATA = [
        "codigo" => "CFMXS",
        "ciclo" => "CFM FCT ESTÈTICA (LEY ORGNAICA GRUPO SENUNDARIA EESPECIAL)",
        "Dept" => "Imagen",
        "cDept" => "Imagen Personal",
        "vDept" => "Imatge Personal",
        "vCiclo"=> "Atenció a persones en situació de dependència,Atención a personas en situación de dependencia"];
    const NOT_RESPONSABLE = [
        "codigo" => "CFMX",
        "ciclo" => "CFM FCT ESTÈTICA (LOGSE)",
        "Dept" => "Img",
        "cDept" => "Imagen Personal",
        "vDept" => "Imatge Personal",
        "responsable" => 220,
        "vCiclo"=> "Atenció a persones en situació de dependència"];

    public function testUnautenticatedFails()
    {
        $this->seed();
        $this->expectedUnauthenticated('POST', self::PETITION,self::CICLO_DATA);
    }

    public function testUnauthorizedFails()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden('POST', self::PETITION,self::CICLO_DATA);
    }

    public function testForRequiredFields()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json('POST', self::PETITION)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "codigo" => ["The codigo field is required."],
                    "ciclo" => ["The ciclo field is required."],
                    "Dept" => ["The dept field is required."],
                    "cDept" => ["The c dept field is required."],
                    "vDept" => ["The v dept field is required."],
                ]
            ]);
    }

    public function testWrongFields()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json('POST', self::PETITION,self::ERROR_DATA)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "codigo" => ["The codigo may not be greater than 4 characters."],
                    "ciclo" => ["The ciclo may not be greater than 50 characters."],
                    "Dept" => ["The dept may not be greater than 3 characters."],
                    "vCiclo" => ["The v ciclo may not be greater than 80 characters."],
                ]
            ]);
    }

    public function testNotExistsResponsable()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json('POST', self::PETITION,self::NOT_RESPONSABLE)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "responsable" => ["The selected responsable is invalid."],
                ]
            ]);
    }


    public function testSuccesful(){
        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json('POST', self::PETITION,self::CICLO_DATA, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
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
