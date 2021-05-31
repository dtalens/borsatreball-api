<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class ResponsablesUpdateTest extends FeatureTestCase
{
    const PETITION = 'api/responsables/2';
    const WRONG_PETITION = 'api/responsables/4';
    const METHOD = 'PUT';
    const DATA = [
            "nombre" => "Leo",
            "apellidos" => "Messi",
        ];
    const INCOMPLETE_DATA = [
        "nombre" => "Johan",
    ];

    public function testSuccesfulUpdate(){
        $this->seed();
        $user = $this->actingAsUser(2);
        $this->json(self::METHOD, self::PETITION,self::DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                        "id" => 2,
                        "nombre" => "Leo",
                        "apellidos" => "Messi",
                        "email" => $user->email,
                    ]]);
    }

    public function testForbiddenAdminUpdate(){

        $this->seed();
        $user = $this->actingAsRol(self::EMPRESA_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::DATA);

    }

    public function testSuccesfulIncompleteData(){
        $this->seed();
        $user = $this->actingAsUser(2);
        $this->json(self::METHOD, self::PETITION,self::INCOMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 2,
                    "nombre" => "Johan",
                ]]);
    }

    public function testUpdateNotChangeId(){
        $alumnoData = self::DATA;
        $alumnoData['id'] = 4;
        $this->seed();
        $user = $this->actingAsUser(2);
        $this->json(self::METHOD, self::PETITION,$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                    "data" => [
                        "id" => 2,
                        "nombre" => "Leo",
                        "apellidos" => "Messi",
                        "email" => $user->email,
                    ]]);
    }

    public function testNotFindUpdateFails()
    {
        $this->seed();
        $this->actingAsUser(1);
        $this->expectedNotFound(self::METHOD, self::WRONG_PETITION,'Responsable');
    }

    public function testUnauthorizedUpdateFails()
    {
        $this->seed();
        $this->actingAsUser(8);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::DATA);
    }

}
