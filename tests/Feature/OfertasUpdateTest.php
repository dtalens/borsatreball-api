<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Oferta;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;

class OfertasUpdateTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas/4';
    const METHOD = 'PUT';
    const DATA = [
        'descripcion' => 'Contrato por obra',
        'estudiando' => 0,
        'ciclos' => [12]
    ];
    const INCOMPLETE_DATA = [
        'id_empresa' => 5
    ];

    public function testUnauthenticated()
    {
        $this->seed();
        $this->expectedUnauthenticated(self::METHOD, self::PETITION,self::DATA);
    }

    public function testAlumnocantPublicOffer(){
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::DATA);
    }

    public function testEmpresacantPublicAnotherOffer(){
        $this->seed();
        $this->actingAsUser(self::ID_EMPRESA_WITH_OFFERS);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::DATA);
    }


    public function testSuccesful()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsUser(self::ID_EMPRESA_WITHOUT_OFFERS);
        $id = $this->json(self::METHOD, self::PETITION, self::DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "id_empresa",
                    "descripcion",
                    "puesto",
                    "tipo_contrato",
                    "activa" ]
            ])
            ->decodeResponseJson()
            ->json()['data']['id'];
        $oferta = Oferta::find($id);
        $this->assertEquals($oferta->validada,0);
        $this->assertEquals(0,$oferta->archivada);
        foreach ($oferta->ciclos as $ciclo) {
            Notification::assertSentTo(
                [User::find($ciclo->responsable)], ValidateOffer::class);
        }

    }

    public function testSuccesfulResponsable()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $id = $this->json(self::METHOD, self::PETITION, self::DATA, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "id_empresa",
                    "descripcion",
                    "puesto",
                    "tipo_contrato",
                    "activa" ]
            ])
            ->decodeResponseJson()
            ->json()['data']['id'];
        $oferta = Oferta::find($id);
        $this->assertEquals('Contrato por obra',$oferta->descripcion);
        $this->assertEquals(0,$oferta->validada);
        $this->assertEquals(0,$oferta->archivada);
        foreach ($oferta->ciclos as $ciclo) {
            Notification::assertSentTo(
                [User::find($ciclo->responsable)], ValidateOffer::class);
        }
    }

    public function testRequiredFieldsForUpdate()
    {
        $this->seed();
        $this->actingAsUser(self::ID_EMPRESA_WITHOUT_OFFERS);
        $this->json(self::METHOD, self::PETITION, self::INCOMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "id_empresa" => ["validation.prohibited"]
                ]
            ]);
    }
}
