<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Oferta;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;

class OfertasStoreTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas';
    const METHOD = 'POST';
    const DATA = [
        'id_empresa' => '6',
        'descripcion' => 'Contrato por obra y servicios hasta acabar obra',
        'estudiando' => 1,
        'ciclos' => [2]
    ];
    const INCOMPLETE_DATA = [
        'descripcion' => 'Contrato por obra y servicios hasta acabar obra',
        'estudiando' => 1,
        'ciclos' => [2]
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
            ->assertStatus(201)
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
            ->assertStatus(201)
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
        $this->assertEquals(0,$oferta->archivada);
        foreach ($oferta->ciclos as $ciclo) {
            Notification::assertSentTo(
                [User::find($ciclo->responsable)], ValidateOffer::class);
        }
    }

    public function testRequiredFieldsForStore()
    {
        $this->seed();
        $this->actingAsUser(self::ID_EMPRESA_WITHOUT_OFFERS);
        $this->json(self::METHOD, self::PETITION, self::INCOMPLETE_DATA, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "id_empresa" => ["The id empresa field is required."]
                ]
            ]);
    }


}
