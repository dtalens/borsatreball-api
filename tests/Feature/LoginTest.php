<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FeatureTestCase;


class LoginTest extends FeatureTestCase
{
    const METHOD = 'POST';

    public function testSuccesfulLogin(){
        $loginData = [
            "email" => "igomis@cipfpbatoi.es",
            "password" => "eiclmp5a",
        ];

        $this->seed();
        $this->json(self::METHOD,'api/auth/login',$loginData,['Accept' => 'application/json'])
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
        $this->json(self::METHOD,'api/auth/login',$loginData,['Accept' => 'application/json'])
            ->assertStatus(421)
            ->assertJson([
                "message" => "Login or password are wrong.",
            ]);
    }

}
