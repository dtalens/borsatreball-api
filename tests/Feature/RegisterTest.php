<?php

namespace Tests\Feature;

use App\Notifications\SignupActivate;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class RegisterTest extends FeatureTestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function testRequiredFieldsForRegistration()
    {

        $this->json('POST', '/api/auth/signup', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "rol" => ["The rol field is required."],
                    "nombre" => ["The nombre field is required."],
                ]
            ]);
    }
    public function testRequiredFieldsForAlumnoRegistration()
    {
        $alumnoData = ['rol' => self::ALUMNO_ROL];
        $this->json('POST', '/api/auth/signup',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "nombre" => ["The nombre field is required."],
                    "domicilio" => ["The domicilio field is required when rol is ".self::ALUMNO_ROL."."],
                    "telefono" => ["The telefono field is required when rol is ".self::ALUMNO_ROL."."],
                    "apellidos" => ["The apellidos field is required when rol is ".self::ALUMNO_ROL."."],
                    "ciclos" => ["The ciclos field is required when rol is ".self::ALUMNO_ROL."."]
                ]
            ]);
    }
    public function testRequiredFieldsForEmpresaRegistration()
    {
        $empresaData = ['rol' => self::EMPRESA_ROL];
        $this->json('POST', '/api/auth/signup',$empresaData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "nombre" => ["The nombre field is required."],
                    "domicilio" => ["The domicilio field is required when rol is ".self::EMPRESA_ROL."."],
                    "telefono" => ["The telefono field is required when rol is ".self::EMPRESA_ROL."."],
                    "cif" => ["The cif field is required when rol is ".self::EMPRESA_ROL."."],
                    "localidad" => ["The localidad field is required when rol is ".self::EMPRESA_ROL."."],
                    "contacto" => ["The contacto field is required when rol is ".self::EMPRESA_ROL."."]
                ]
            ]);
    }

    public function testFailAlumnoWithOutCiclosRegistration()
    {

        $alumnoData = [
            "nombre" => "John",
            "email" => "doe@example.com",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "apellidos" => "Doe",
            'rol' => self::ALUMNO_ROL,
            "password" => "demo12345",
            "password_confirmation" => "demo12345",

        ];
        $this->seed();
        $this->json('POST', '/api/auth/signup',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "ciclos" => ["The ciclos field is required when rol is ".self::ALUMNO_ROL."."],
                ]
            ]);
    }

    public function testSuccessfulAlumnoWithCiclosRegistration()
    {
        Notification::fake();
        Notification::assertNothingSent();


        $alumnoData = [
            "nombre" => "John",
            "email" => "doe@example.com",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "apellidos" => "Doe",
            'rol' => self::ALUMNO_ROL,
            'ciclos' => [2,4],
            "password" => "demo12345",
            "password_confirmation" => "demo12345",

        ];
        $this->seed();
        $id = $this->json('POST', 'api/auth/signup', $alumnoData, ['Accept' => 'application/json'])
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

        Notification::assertSentTo(
            [User::find($id)], SignupActivate::class
        );

        $this->actingAsRol(self::ADMIN_ROL);
        $alumno = $this->getDataFromJson('GET','api/alumnos')[2];
        $this->assertEquals(2,$alumno['ciclos'][0]['id_ciclo']);
        $this->assertEquals(4,$alumno['ciclos'][1]['id_ciclo']);
    }

    public function testDuplicateEmailRegistration()
    {

        $alumnoData = [
            "nombre" => "John",
            "email" => "doe@example.com",
            "domicilio" => 'C/Cid 99',
            "telefono" => "543345657",
            "apellidos" => "Doe",
            'rol' => self::ALUMNO_ROL,
            'ciclos' => [2],
            "password" => "demo12345",
            "password_confirmation" => "demo12345",

        ];
        $this->seed();
        $this->json('POST', 'api/auth/signup', $alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(201);
        $this->json('POST', '/api/auth/signup',$alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email has already been taken."]
                 ]
            ]);
    }


    public function testSuccessfulEmpresaRegistration()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $empresaData = [
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
        $this->seed();
        $id = $this->json('POST', 'api/auth/signup', $empresaData, ['Accept' => 'application/json'])
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
        Notification::assertSentTo(
            [User::find($id)], SignupActivate::class
        );
    }

    public function testFakeRolRegistration()
    {
        $empresaData = [
            "nombre" => "Aitex",
            "email" => "aitex@example.com",
            "domicilio" => 'Batoi',
            "telefono" => "543325657",
            'rol' => self::FAKE_ROL,
            'cif' => 'A12345678',
            'contacto' => 'Pepe Botera',
            'localidad' => 'Batoi',
            "password" => "demo12345",
            "password_confirmation" => "demo12345",

        ];
        $this->seed();
        $this->json('POST', 'api/auth/signup', $empresaData, ['Accept' => 'application/json'])
            ->assertStatus(405)
            ->assertJson([
                "message" => "The given rol was invalid.",
            ]);
    }



}
