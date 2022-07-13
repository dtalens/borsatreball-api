<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class AlumnosPostTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos';
    const METHOD = 'POST';
    const DATA = [
            "nombre" => "John",
            "email" => "doe@example.com",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "apellidos" => "Doe",
            'rol' => self::ALUMNO_ROL,
            "password" => "demo12345",
            "password_confirmation" => "demo12345",

        ];

    public function testUnauhtorizeAdminPostFails()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->expectedRouteNotFound(self::METHOD, self::PETITION,self::DATA);
    }

    public function testUnauhtorizeAlumnoPostFails()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedRouteNotFound(self::METHOD, self::PETITION,self::DATA);
    }

}
