<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Responsable;

class ResponsablesIndexTest extends FeatureTestCase
{
    const PETITION = 'api/responsables';
    const METHOD = 'GET';


    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION);
    }

    public function testIndexAlumnoFails()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION);
    }

    public function testIndexAdminReturnAll()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $items = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->assertEquals(count($items),count(Responsable::all()));
    }


}
