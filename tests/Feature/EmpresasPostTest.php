<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class EmpresasPostTest extends FeatureTestCase
{
    const PETITION = 'api/empresas';
    const METHOD = 'POST';
    const DATA = [
        "nombre" => "Aitex",
        "email" => "aitex@example.com",
        "domicilio" => 'Batoi',
        "telefono" => "543325657",
        'rol' => self::EMPRESA_ROL,
        'cif' => 'A12345678',
        'contacto' => 'Pepe Botera',
        'localidad' => 'Batoi',
        "password" => "demo12345",
        "password_confirmation" => "demo12345",

    ];

    public function testUnauhtorizeAdminPostFails()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->expectedRouteNotFound(self::METHOD, self::PETITION,self::DATA);
    }

    public function testUnauhtorizeEmpresaPostFails()
    {
        $this->seed();
        $this->actingAsRol(self::EMPRESA_ROL);
        $this->expectedRouteNotFound(self::METHOD, self::PETITION,self::DATA);
    }

}
