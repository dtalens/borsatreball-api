<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FeatureTestCase;


class AuthTest extends FeatureTestCase
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
                    "domicilio" => ["The domicilio field is required."],
                    "telefono" => ["The telefono field is required."],
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
                    "domicilio" => ["The domicilio field is required."],
                    "telefono" => ["The telefono field is required."],
                    "apellidos" => ["The apellidos field is required when rol is ".self::ALUMNO_ROL."."]
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
                    "domicilio" => ["The domicilio field is required."],
                    "telefono" => ["The telefono field is required."],
                    "cif" => ["The cif field is required when rol is ".self::EMPRESA_ROL."."],
                    "localidad" => ["The localidad field is required when rol is ".self::EMPRESA_ROL."."],
                    "contacto" => ["The contacto field is required when rol is ".self::EMPRESA_ROL."."]
                ]
            ]);
    }

    public function testSuccessfulAlumnoRegistration()
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
        $this->json('POST', 'api/auth/signup', $alumnoData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    "access_token",
                    "rol",
                    "token_type",
                    "id",
                    "name",
                    "expires_at" ]
            ]);
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
        $this->json('POST', 'api/auth/signup', $empresaData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    "access_token",
                    "rol",
                    "token_type",
                    "id",
                    "name",
                    "expires_at" ]
            ]);
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

    public function testSuccesfulLogin(){
        $loginData = [
            "email" => "igomis@cipfpbatoi.es",
            "password" => "eiclmp5a",
        ];

        $this->seed();
        $this->json('POST','api/auth/login',$loginData,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                "access_token",
                "rol",
                "token_type",
                "id",
                "name",
                "expires_at" ]
            ]);
    }

    public function testFailLogin(){
        $loginData = [
            "email" => "igomis@cipfpbatoi.es",
            "password" => "eiclmp5",
        ];

        $this->seed();
        $this->json('POST','api/auth/login',$loginData,['Accept' => 'application/json'])
            ->assertStatus(421)
            ->assertJson([
                "message" => "Login or password are wrong.",
            ]);
    }

}
