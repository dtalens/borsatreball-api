<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;

class ResponsablesStoreTest extends FeatureTestCase
{
    const PETITION = 'api/responsables';
    const METHOD = 'POST';
    const DATA_RESPONSABLE = [
            "nombre" => "John",
            "email" => "responsable@example.com",
            "apellidos" => "Responsable",
            "rol" => self::RESPONSABLE_ROL,
            "password" => "demo12345",
            "password_confirmation" => "demo12345",

        ];
    const DATA_ADMIN = [
        "nombre" => "John",
        "email" => "admin@example.com",
        "apellidos" => "Administrador",
        "rol" => self::ADMIN_ROL,
        "password" => "demo12345",
        "password_confirmation" => "demo12345",

    ];

    public function testAdminCreateResponsableSuccesfull()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $id = $this->json(self::METHOD, self::PETITION,self::DATA_RESPONSABLE, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    "access_token",
                    "rol",
                    "token_type",
                    "id",
                    "name",
                    "expires_at" ]
            ])
            ->decodeResponseJson()
            ->json()['data']['id'];

        $user = User::find($id);
        Notification::assertSentTo(
            [$user], SignupActivate::class
        );

        $this->assertEquals(self::RESPONSABLE_ROL,$user->rol);
        $this->actingAsRol(self::ADMIN_ROL);
        $responsable = $this->getDataFromJson('GET','api/responsables/'.$id);
        $this->assertEquals('John',$responsable['nombre']);
        $this->assertEquals('Responsable',$responsable['apellidos']);
    }

    public function testAdminCreateAdminSuccesfull()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $id = $this->json(self::METHOD, self::PETITION,self::DATA_ADMIN, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    "access_token",
                    "rol",
                    "token_type",
                    "id",
                    "name",
                    "expires_at" ]
            ])
            ->decodeResponseJson()
            ->json()['data']['id'];

        $user = User::find($id);
        Notification::assertSentTo(
            [$user], SignupActivate::class
        );

        $this->assertEquals(self::ADMIN_ROL,$user->rol);
        $this->actingAsRol(self::ADMIN_ROL);
        $responsable = $this->getDataFromJson('GET','api/responsables/'.$id);
        $this->assertEquals('John',$responsable['nombre']);
        $this->assertEquals('Administrador',$responsable['apellidos']);
    }

    public function testUnauhtorizeResponsablePostFails()
    {
        $this->seed();
        $this->actingAsRol(self::RESPONSABLE_ROL);
        $this->expectedForbidden(self::METHOD, self::PETITION,self::DATA_RESPONSABLE);
    }

    public function testRequiredFieldsForResponsableRegistration()
    {
        $responsableData = ['rol' => self::RESPONSABLE_ROL];
        $this->seed();
        $this->actingAsRol(self::ADMIN_ROL);
        $this->json(self::METHOD, self::PETITION,$responsableData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "nombre" => ["The nombre field is required."],
                    "apellidos" =>["The apellidos field is required when rol is ".self::RESPONSABLE_ROL."."]
                 ]
            ]);
    }

}
