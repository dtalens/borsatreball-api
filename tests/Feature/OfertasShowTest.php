<?php

namespace Tests\Feature;


use Tests\FeatureTestCase;

class OfertasShowTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas/1';
    const WRONG_PETITION = 'api/ofertas/40';
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
        $this->assertEquals(self::OFERTA_FIELDS,array_keys($item));
    }

    public function testAlumnoReturnAllFields()
    {
        $this->seed();
        $this->actingAsUser(3);
        $item = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->assertEquals(self::OFERTA_FIELDS_ALUMNO,array_keys($item));
    }


    public function testEmptyAnswer()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->expectedNotFound(self::METHOD,self::WRONG_PETITION,'Oferta');
    }
}
