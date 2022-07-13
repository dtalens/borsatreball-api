<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;



class MenuIndexTest extends FeatureTestCase
{
    const PETITION = 'api/menu';
    const FIELDS = ['id','order','icon','text','path','rol','parent','model','active','comments','icon_alt'];
    const METHOD = 'GET';

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION);
    }

    public function testSuccessfulAlumno()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $itemsMenu = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->checkItemsMatchRol($itemsMenu,self::ALUMNO_ROL);
    }

    public function testSuccessfulEmpresa()
    {
        $this->seed();
        $this->actingAsRol(self::EMPRESA_ROL);
        $itemsMenu = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->checkItemsMatchRol($itemsMenu,self::EMPRESA_ROL);
    }

    public function testSuccessfulAdmin()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $itemsMenu = $this->getDataFromJson(self::METHOD,self::PETITION);
        $this->checkItemsMatchRol($itemsMenu,self::ADMIN_ROL);
    }

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $itemsMenu = $this->getDataFromJson(self::METHOD,self::PETITION)[0];
        $this->assertEquals(self::FIELDS,array_keys($itemsMenu));
    }

    private function checkItemsMatchRol($itemsMenu,$rol){
        foreach ($itemsMenu as $item){
            $this->assertEquals(0,$item['rol'] % $rol);
        }
    }

}
