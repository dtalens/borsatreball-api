<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\OfferStudent;
use App\Notifications\ValidateOffer;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;


class InteresAlumnoOfertaTest extends FeatureTestCase
{
    const PETITION = 'api/ofertas/2/alumno';
    const PETITION_NOTVALIDADA = 'api/ofertas/4/alumno';

    const INTERESADO = [
        "interesado" => true,
    ];
    const NOINTERESADO = [
        "interesado" => false,
    ];
    const METHOD = 'PUT';



    public function testSuccesful()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsUser(3);
        $oferta = $this->getDataFromJson(self::METHOD,self::PETITION,self::INTERESADO);
        $this->assertEquals(1,$oferta['interesado']);
        Notification::assertSentTo(
            [User::find(5)], OfferStudent::class);
    }

    public function testNotInterestedSuccesful()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsUser(4);
        $oferta = $this->getDataFromJson(self::METHOD,self::PETITION,self::NOINTERESADO);
        $this->assertEquals(0,$oferta['interesado']);
        Notification::assertNotSentTo(
            [User::find(5)], ValidateOffer::class);
    }

    public function testNotInterestedFromEmptySuccesfull()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsUser(3);
        $oferta = $this->getDataFromJson(self::METHOD,self::PETITION,self::NOINTERESADO);
        $this->assertEquals(0,$oferta['interesado']);
        Notification::assertNotSentTo(
            [User::find(5)], OfferStudent::class);
    }


    public function testNotValidadaFails()
    {
        $this->seed();
        $this->actingAsUser(4);
        $this->expectedRouteNotFound(self::METHOD,self::PETITION_NOTVALIDADA,self::INTERESADO);
    }

    public function testArchivadaFails()
    {
        $this->seed();
        $this->actingAsUser(3);
        $this->expectedRouteNotFound(self::METHOD,self::PETITION_NOTVALIDADA,self::INTERESADO);
    }

    public function testNotAllowed()
    {
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::INTERESADO);
    }
}
