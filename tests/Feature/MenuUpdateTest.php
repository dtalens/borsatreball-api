<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class MenuUpdateTest extends FeatureTestCase
{
    const PETITION = 'api/menu/1';
    const METHOD = 'PUT';
    const DATA = [
            "order" => "3",
            "icon" => 'jirafa',
            "text" => "Bienvenida",
            "path" => "/hola"
        ];


    public function testSuccesfulUpdate(){
        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION,self::DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                        "id" => 1,
                        "order" => "3",
                    "icon" => 'jirafa',
                    "text" => "Bienvenida",
                    "path" => "/hola"
                    ]]);
    }

    public function testForbiddenResponsableUpdate(){

        $this->seed();
        $user = $this->actingAsRol(self::RESPONSABLE_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::DATA);

    }

    public function testUpdateNotChangeId(){
        $alumnoData = self::DATA;
        $alumnoData['id'] = 4;
        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION,$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 1,
                    "order" => "3",
                    "icon" => 'jirafa',
                    "text" => "Bienvenida",
                    "path" => "/hola"
                ]]);
    }


}
