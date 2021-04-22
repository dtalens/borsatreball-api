<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\Models\Alumno;

class AlumnoShowTest extends FeatureTestCase
{
    const PETITION = 'api/alumnos';
    const FIELDS = ['id','nombre','apellidos','domicilio','info','bolsa','cv_enlace','telefono','email','ciclos'];
    const ID_EMPRESA_WITHOUT_OFFERS = 6;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUnauthenticated()
    {
        $this->seed();
        $this->json('GET', self::PETITION)
            ->assertStatus(421)
            ->assertJson([
                "message" => "Unauthenticated.",
            ]);
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

    public function testReturnAllFields()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson('GET',self::PETITION)[0];
        $this->assertEquals(self::FIELDS,array_keys($item));
    }

    public function testReturnCiclos()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $item = $this->getDataFromJson('GET',self::PETITION)[0]['ciclos'];
        $this->assertEquals(3,count($item));
    }

    public function testErrorAnotherShow()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->json('GET', self::PETITION.'/4')
            ->assertStatus(405)
            ->assertJson([
                "message" => "Forbidden.",
            ]);
    }

    public function testSuccesfulSelfShow()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->json('GET', self::PETITION.'/3')
            ->assertStatus(200);
    }



}
