<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class AlumnosUpdateTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos/3';
    const WRONG_PETITION = 'api/alumnos/4';
    const METHOD = 'PUT';
    const COMPLETE_DATA_WITH_CICLO = [
            "nombre" => "John",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "ciclos" => [2,16,18],
            "apellidos" => "Doe",
        ];
    const COMPLETE_DATA_WITH_SAME_CICLO = [
        "nombre" => "John",
        "domicilio" => 'C/Cid 99',
        "ciclos" => [2,3,4],
        "apellidos" => "Doe",
    ];
    const COMPLETE_DATA_WHITOUT_CICLO = [
        "nombre" => "John",
        "domicilio" => 'C/Cid 99',
        "apellidos" => "Doe",
    ];
    const INCOMPLETE_DATA = [
        "nombre" => "John",
    ];

    public function testSuccesfulUpdate(){

        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION,SELF::COMPLETE_DATA_WITH_CICLO, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                        "id" => 3,
                        "nombre" => "John",
                        "apellidos" => "Doe",
                        "domicilio" => 'C/Cid 99',
                        "info" => 1,
                        "bolsa" => 1,
                        "cv_enlace" => null,
                        "telefono" => "543345657",
                        "email" => $user->email,
                        "ciclos" => [['id_alumno'=>3,'id_ciclo'=>2,'any'=>2016,'validado'=>1],['id_alumno'=>3,'id_ciclo'=>16,'any'=>null,'validado'=>0],['id_alumno'=>3,'id_ciclo'=>18,'any'=>null,'validado'=>0]]
                    ]]);
    }

    public function testForbiddenAdminUpdate(){

        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::COMPLETE_DATA_WITH_CICLO);

    }

    public function testSuccesfulUpdateManteinsValidadoCiclo(){

        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION,SELF::COMPLETE_DATA_WHITOUT_CICLO, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 3,
                    "nombre" => "John",
                    "apellidos" => "Doe",
                    "domicilio" => 'C/Cid 99',
                    "info" => 1,
                    "bolsa" => 1,
                    "cv_enlace" => null,
                    "telefono" => "655556777",
                    "email" => $user->email,
                    "ciclos" => [['id_alumno'=>3,'id_ciclo'=>2,'any'=>'2016','validado'=>1],['id_alumno'=>3,'id_ciclo'=>3,'any'=>'2018','validado'=>1],['id_alumno'=>3,'id_ciclo'=>12,'any'=>null,'validado'=>1]]
                ]]);
    }

    public function testSuccesfulUpdateManteinsCiclos(){

        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION,SELF::COMPLETE_DATA_WITH_SAME_CICLO, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 3,
                    "nombre" => "John",
                    "apellidos" => "Doe",
                    "domicilio" => 'C/Cid 99',
                    "info" => 1,
                    "bolsa" => 1,
                    "cv_enlace" => null,
                    "telefono" => "655556777",
                    "email" => $user->email,
                    "ciclos" => [['id_alumno'=>3,'id_ciclo'=>2,'any'=>'2016','validado'=>1],['id_alumno'=>3,'id_ciclo'=>3,'any'=>'2018','validado'=>1],['id_alumno'=>3,'id_ciclo'=>4,'any'=>null,'validado'=>0]]
                ]]);
    }

    public function testSuccesfulIncompleteData(){
        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION,SELF::INCOMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 3,
                    "nombre" => "John",
                ]]);
    }

    public function testFailIfCicloNotExistsUpdate(){
        $alumnoData = [
            "nombre" => "John",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "ciclos" => [5,6],
            "apellidos" => "Doe",
        ];
        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION,$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(500)
            ->assertJson([
                "message" => "BD error.",
            ]);
    }

    public function testUpdateNotChangeId(){
        $alumnoData = self::COMPLETE_DATA_WITH_SAME_CICLO;
        $alumnoData['id'] = 4;
        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json(self::METHOD, self::PETITION,$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                        "id" => 3,
                        "nombre" => "John",
                        "apellidos" => "Doe",
                        "domicilio" => 'C/Cid 99',
                        "info" => 1,
                        "bolsa" => 1,
                        "cv_enlace" => null,
                        "telefono" => "655556777",
                        "email" => $user->email,
                    ]]);
    }

    public function testUnauhtorizeUpdateFails()
    {
        $this->seed();
        $this->actingAsUser(3);
        $this->expectedForbidden(self::METHOD, self::WRONG_PETITION,self::COMPLETE_DATA_WITH_SAME_CICLO);
    }

}
