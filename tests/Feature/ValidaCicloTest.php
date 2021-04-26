<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FeatureTestCase;
use Tests\TestCase;

class ValidaCicloTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos/3/ciclo/4';
    const CICLODATA = [
        "any" => '2020',
        "validado" => 1,
    ];

    public function testSuccesful()
    {

        $expectedResult =[
            "id_alumno" => 3,
            "id_ciclo" => 4,
            "any" => '2020',
            "validado" => 1,
        ];
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $ciclos = $this->getDataFromJson('PUT',self::PETITION,self::CICLODATA)['ciclos'];
        $this->assertEquals($expectedResult,$ciclos[2]);
    }

    public function testNotAllowed()
    {
        $cicloData = [
            "any" => '2020',
            "validado" => 1,
        ];

        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden('PUT', self::PETITION,self::CICLODATA);
    }
}
