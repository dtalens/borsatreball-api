<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Alumno;

class ResponsablesShowTest extends FeatureTestCase
{
    const PETITION = 'api/responsables/2';
    const WRONG_PETITION = 'api/responsables/20';
    const METHOD = 'GET';

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION);
    }

    public function testSuccesful()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION)
            ->assertStatus(200);
    }

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $item = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->assertEquals(self::RESPONSABLE_FIELDS,array_keys($item));
    }

    public function testReturnCiclos()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $item = $this->getDataFromJson(self::METHOD,self::PETITION)['ciclos'];
        $this->assertEquals(5,count($item));
    }

    public function testErrorAnotherShow()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->expectedNotFound(self::METHOD, self::WRONG_PETITION,'Responsable');

    }



}
