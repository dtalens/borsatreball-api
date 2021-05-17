<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Alumno;

class AlumnosShowTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos/3';
    const WRONG_PETITION = 'api/alumnos/4';
    const METHOD = 'GET';

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION);
    }

    public function testSuccesfulSelf()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200);
    }

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->assertEquals(self::ALUMNO_FIELDS,array_keys($item));
    }

    public function testReturnCiclos()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson(self::METHOD,self::PETITION)['ciclos'];
        $this->assertEquals(3,count($item));
    }

    public function testErrorAnotherShow()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden(self::METHOD, self::WRONG_PETITION);

    }



}
