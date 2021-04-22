<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class AlumnoModifyTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos';
    const FIELDS = ['id','nombre','apellidos','domicilio','info','bolsa','cv_enlace','telefono','email','ciclos'];
    const ID_EMPRESA_WITHOUT_OFFERS = 6;


    public function testSuccesfulUpdate(){
        $alumnoData = [
            "nombre" => "John",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "ciclos" => [16,18],
            "apellidos" => "Doe",
        ];
        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json('PUT', self::PETITION.'/3',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => 3,
                        "nombre" => "John",
                        "apellidos" => "Doe",
                        "domicilio" => 'C/Cid 99',
                        "info" => 1,
                        "bolsa" => 1,
                        "cv_enlace" => null,
                        "telefono" => "543345657",
                        "email" => $user->email,
                        "ciclos" => [['id_alumno'=>3,'id_ciclo'=>16],['id_alumno'=>3,'id_ciclo'=>18]]
                    ]]]);
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
        $this->json('PUT', self::PETITION.'/3',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(500)
            ->assertJson([
                "message" => "BD error.",
            ]);
    }

    public function testUpdateNotChangeID(){
        $alumnoData = [
            "id" => 4,
            "nombre" => "John",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "apellidos" => "Doe",
        ];
        $this->seed();
        $user = $this->actingAsUser(3);
        $this->json('PUT', self::PETITION.'/3',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => 3,
                        "nombre" => "John",
                        "apellidos" => "Doe",
                        "domicilio" => 'C/Cid 99',
                        "info" => 1,
                        "bolsa" => 1,
                        "cv_enlace" => null,
                        "telefono" => "543345657",
                        "email" => $user->email,
                    ]]]);
    }

    public function testUnauhtorizeUpdate()
    {
        $alumnoData = [
            "id" => 4,
            "nombre" => "John",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "apellidos" => "Doe",
        ];
        $this->seed();
        $this->actingAsUser(3);
        $this->json('PUT', self::PETITION.'/4',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(405)
            ->assertJson([
                "message" => "Forbidden.",
            ]);
    }

}
