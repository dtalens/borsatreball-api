<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Oferta;

class OfertasIndexTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas';
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
        $this->assertEquals(count($items),count(Oferta::all()));
    }

    public function testIndexResponsableReturnOffersCicle()
    {
        $this->seed();
        $user = $this->actingAsRol(self::RESPONSABLE_ROL);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(count($items),1);
        $item = $items[0];
        $this->assertEquals(12,$item['ciclos'][0]['id_ciclo']);
    }


    public function testIndexAlumnoReturnOffersCicle()
    {
        $this->seed();
        $user = $this->actingAsUser(3);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(1,count($items));
    }

    public function testIndexEstudianteReturnOffersCicle()
    {
        $this->seed();
        $user = $this->actingAsUser(4);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(2,count($items));
    }

/*

    public function testIndexResponsableReturnAlumnesCicle()
    {
        $this->seed();
        $user = $this->actingAsRol(self::RESPONSABLE_ROL);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(count($items),1);
        $alumno = $items[0];
        $this->assertEquals(4,$alumno['id']);
    }

    public function testIndexEnterpriseReturnInterested()
    {
        $this->seed();
        $user = $this->actingAsRol(self::EMPRESA_ROL);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(count($items),1);
        $this->actingAsUser(self::ID_EMPRESA_WITHOUT_OFFERS);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(count($items),0);
    }

*/


}
