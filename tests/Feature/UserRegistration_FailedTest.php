<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistration_FailedTest extends TestCase
{
    public function test_register_unsupported_method()
    {
        $response = $this->get('/api/register');

        $response->assertStatus(405);
    }

    public function data_provider_wrong_params()
    {
        return [
            [
                "name" => "",
                "email" => "member@gmail.com",
                "pass" => "pass123",
                "expectedResponse" => [
                    "message" => "The name field is required.",
                    "errors" => [
                        "name" => ["The name field is required."]
                    ]
                ]
            ],
            [
                "name" => "Name",
                "email" => "",
                "pass" => "pass123",
                "expectedResponse" => [
                    "message" => "The email field is required.",
                    "errors" => [
                        "email" => ["The email field is required."]
                    ]
                ]
            ],
            [
                "name" => "Name",
                "email" => "member@gmail.com",
                "pass" => "",
                "expectedResponse" => [
                    "message" => "The password field is required.",
                    "errors" => [
                        "password" => ["The password field is required."]
                    ]
                ]
            ],
            [
                "name" => "Name",
                "email" => "member@gmail.com",
                "pass" => "123",
                "expectedResponse" => [
                    "message" => "The password must be at least 7 characters.",
                    "errors" => [
                        "password" => ["The password must be at least 7 characters."]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider data_provider_wrong_params
     */
    public function test_register_wrong_params($name, $email, $pass, $expectedError)
    {
        $response = $this->post(
            '/api/register',
            ['name' => $name, 'email' => $email, 'password' => $pass]
        );

        $response->assertStatus(422);
        $response->assertExactJson($expectedError);
    }
}
