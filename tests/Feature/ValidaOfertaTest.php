<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\OfferStudent;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;


class ValidaOfertaTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas/4/validar';
    const VALIDADA = [
        "validada" => true,
    ];
    const NOVALIDADA = [
        "validada" => false,
    ];
    const METHOD = 'PUT';



    public function testSuccesful()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $oferta = $this->getDataFromJson(self::METHOD,self::PETITION,self::VALIDADA);
        $this->assertEquals(1,$oferta['validada']);
        Notification::assertSentTo(
            [User::find(3)], OfferStudent::class);
        Notification::assertNotSentTo(
            [User::find(4)], OfferStudent::class);
    }



    public function testDesvalidarSuccesful()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $oferta = $this->getDataFromJson(self::METHOD,self::PETITION,self::NOVALIDADA);
        $this->assertEquals(0,$oferta['validada']);
        Notification::assertNotSentTo(
            [User::find(3)], OfferStudent::class);
    }

    public function testNotAllowed()
    {
        $this->seed();
        $this->actingAsRol(self::ALUMNO_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::VALIDADA);
    }
}
