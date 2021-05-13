<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Alumno;

class AlumnosShowTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos/3';
    const WRONG_PETITION = 'api/alumnos/4';
    const FIELDS = ['id','nombre','apellidos','domicilio','info','bolsa','cv_enlace','telefono','email','ciclos','created_at','updated_at'];

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated('GET', self::PETITION);
    }

    public function testSuccesfulSelf()
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

    public function testReturnCiclos()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson('GET',self::PETITION)['ciclos'];
        $this->assertEquals(3,count($item));
    }

    public function testErrorAnotherShow()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden('GET', self::WRONG_PETITION);

    }



}
