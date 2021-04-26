<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class EmpresasUpdateTest extends FeatureTestCase
{
    const PETITION = 'api/empresas/5';
    const WRONG_PETITION = 'api/empresas/6';
    const METHOD = 'PUT';
    const COMPLETE_DATA = [
        "cif" => '12344321Q',
        "nombre" => "Tomate Frito",
        "domicilio" => 'C/Cid 99',
        "telefono" => "543345657",
        "localidad" => "IBI",
        "contacto" => "Pepe",
        "web" => 'www.pepe.es',
        "descripcion" => 'Chapucillas SA'
    ];
    const INCOMPLETE_DATA = [
        "cif" => '12354321Q',
    ];


    public function testSuccesfulUpdate()
    {
        $this->seed();
        $user = $this->actingAsUser(5);
        $this->json(self::METHOD, self::PETITION, self::COMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 5,
                    "cif" => '12344321Q',
                    "nombre" => "Tomate Frito",
                    "domicilio" => 'C/Cid 99',
                    "localidad" => "IBI",
                    "contacto" => 'Pepe',
                    "telefono" => "543345657",
                    "email" => $user->email,
                    "web" => 'www.pepe.es',
                    "descripcion" => "Chapucillas SA"
                ]]);
    }

    public function testSuccesfulUpdateIncompleteData()
    {
        $this->seed();
        $user = $this->actingAsUser(5);
        $this->json(self::METHOD, self::PETITION, self::INCOMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 5,
                    "cif" => '12354321Q',
                    "nombre" => "Aitex",
                ]]);
    }

    public function testSuccesfulResponsableUpdate()
    {
        $this->seed();
        $this->actingAsUser(2);
        $this->json(self::METHOD, self::PETITION, self::COMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => 5,
                    "cif" => '12344321Q',
                    "nombre" => "Tomate Frito",
                    "domicilio" => 'C/Cid 99',
                    "localidad" => "IBI",
                    "contacto" => 'Pepe',
                    "telefono" => "543345657",
                ]]);
    }

    public function testFailWrongEnterpriseUpdate()
    {
        $this->seed();
        $this->actingAsUser(6);
        $this->expectedForbidden(self::METHOD, self::PETITION, self::COMPLETE_DATA, ['Accept' => 'application/json']);
    }

    public function testFailAlumnoUpdate()
    {
        $this->seed();
        $this->actingAsUser(3);
        $this->expectedForbidden(self::METHOD, self::PETITION, self::COMPLETE_DATA, ['Accept' => 'application/json']);
    }

    public function testUpdateNotChangeId()
    {

        $this->seed();
        $user = $this->actingAsUser(5);
        $data = SELF::COMPLETE_DATA;
        $data['id'] = '6';
        $this->json(self::METHOD, self::PETITION, $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([

                "data" => [
                    "id" => 5,
                    "cif" => '12344321Q',
                    "nombre" => "Tomate Frito",
                    "domicilio" => 'C/Cid 99',
                    "localidad" => "IBI",
                    "contacto" => 'Pepe',
                    "telefono" => "543345657",
                    "email" => $user->email,
                    "web" => 'www.pepe.es',
                    "descripcion" => "Chapucillas SA"
                ]]);
    }
}

