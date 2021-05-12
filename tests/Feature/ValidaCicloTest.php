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
        "validado" => true,
    ];
    const DESCICLODATA = [
        "any" => '',
        "validado" => false,
    ];

    public function testvalidationYearError()
    {
        $year = date('Y');
        $errorData = [
            "any" => $year+1,
            "validado" => true
        ];
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $this->json('PUT', self::PETITION,$errorData)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "any" => ["The any may not be greater than ".$year."."],
                 ]
            ]);
    }

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

    public function testDesValidarSuccesful()
    {
        $expectedResult =[
            "id_alumno" => 3,
            "id_ciclo" => 4,
            "validado" => 0,
            "any" => 0
        ];
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $ciclos = $this->getDataFromJson('PUT',self::PETITION,self::DESCICLODATA)['ciclos'];
        $this->assertEquals($expectedResult,$ciclos[2]);
    }

    public function testNotAllowed()
    {
        $cicloData = [
            "any" => '2020',
            "validado" => true,
        ];

        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden('PUT', self::PETITION,self::CICLODATA);
    }
}
