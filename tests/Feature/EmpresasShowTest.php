<?php

namespace Tests\Feature;


use Tests\FeatureTestCase;

class EmpresasShowTest extends FeatureTestCase
{
    const PETITION = 'api/empresas/5';
    const WRONG_PETITION = 'api/empresas/1';
    const METHOD = 'GET';

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION);
    }

    public function testForbiddenForAlumno()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

    public function testSuccesful()
    {
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200);
    }

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $item = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->assertEquals(self::EMPRESA_FIELDS,array_keys($item));
    }

    public function testErrorAnotherForbidden()
    {
        $this->seed();
        $this->actingAsUser(6);
        $this->expectedForbidden(self::METHOD,self::PETITION);
    }
    public function testEmptyAnswer()
    {
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $this->expectedNotFound(self::METHOD,self::WRONG_PETITION,'Empresa');
    }
}
