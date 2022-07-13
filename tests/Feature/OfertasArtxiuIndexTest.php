<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Oferta;

class OfertasArtxiuIndexTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas-arxiu';
    const METHOD = 'GET';

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION);
    }

    public function testIndexAdminReturnAll()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $items = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->assertEquals(1,count($items));
    }


    public function testIndexAlumnoReturnEmpty()
    {
        $this->seed();
        $user = $this->actingAsUser(3);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(0,count($items));
    }

    public function testIndexEnterpriseReturnEmpty()
    {
        $this->seed();
        $user = $this->actingAsUser(5);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(0,count($items));
    }
}
