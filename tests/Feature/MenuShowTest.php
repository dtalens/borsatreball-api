<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;



class MenuShowTest extends FeatureTestCase
{
    const PETITION = 'api/menu';
    const FIELDS = ['id','order','icon','text','path','rol','parent','model','active','comments','icon_alt'];

    public function testUnAuthenticated()
    {
        $this->seed();
        $this->json('GET', self::PETITION)
            ->assertStatus(421)
            ->assertJson([
                "message" => "Unauthenticated.",
            ]);
    }

    public function testSuccessfulAlumno()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $itemsMenu = $this->getDataFromJson('GET',self::PETITION);
        $this->checkItemsMatchRol($itemsMenu,self::ALUMNO_ROL);
    }

    public function testSuccessfulEmpresa()
    {
        $this->seed();
        $this->actingAsRol(self::EMPRESA_ROL);
        $itemsMenu = $this->getDataFromJson('GET',self::PETITION);
        $this->checkItemsMatchRol($itemsMenu,self::EMPRESA_ROL);
    }

    public function testSuccessfulAdmin()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $itemsMenu = $this->getDataFromJson('GET',self::PETITION);
        $this->checkItemsMatchRol($itemsMenu,self::ADMIN_ROL);
    }

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $itemsMenu = $this->getDataFromJson('GET',self::PETITION)[0];
        $this->assertEquals(self::FIELDS,array_keys($itemsMenu));
    }

    private function checkItemsMatchRol($itemsMenu,$rol){
        foreach ($itemsMenu as $item){
            $this->assertEquals(0,$item['rol'] % $rol);
        }
    }

}
