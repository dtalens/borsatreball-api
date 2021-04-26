<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Alumno;

class AlumnosIndexTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos';
    const ID_EMPRESA_WITHOUT_OFFERS = 6;

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated('GET', self::PETITION);
    }

    public function testIndexAlumnoReturnSelf()
    {
        $this->seed();
        $user = $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson('GET',self::PETITION)[0];
        $this->assertEquals($user->id,$item['id']);
    }

    public function testIndexAdminReturnAll()
    {
        $this->seed();
        $user = $this->actingAsRol(self::ADMIN_ROL);
        $items = $this->getDataFromJson('GET',self::PETITION);
        $this->assertEquals(count($items),count(Alumno::all()));
    }

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




}
