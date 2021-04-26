<?php

namespace Tests\Feature;


use Tests\FeatureTestCase;

class CiclosShowTest extends FeatureTestCase
{
    const PETITION = 'api/ciclos/2';
    const WRONG_PETITION = 'api/ciclos/1';
    const FIELDS = ['id','codigo','ciclo','Dept','cDept','vDept','responsable','vCiclo','cCiclo'];


    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated('GET', self::PETITION);
    }

    public function testSuccesful()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->json('GET', self::PETITION)
            ->assertStatus(200);
    }

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(self::FIELDS,array_keys($item));
    }

    public function testEmptyAnswer()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedNotFound('GET',self::WRONG_PETITION,'Ciclo');
    }
}
