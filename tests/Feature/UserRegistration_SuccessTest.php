<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistration_SuccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_on_register_create_user_and_login()
    {
        // WHEN

        $response = $this->post(
            '/api/register',
            [
                'name' => 'Name',
                'email' => 'member@gmail.com',
                'password' => 'pass123'
            ]
        );

        // THEN

        $this->assertDatabaseHas('users', [
            'name' => 'Name',
            'email' => 'member@gmail.com',
            'roles' => "[\"ROLE_MEMBER\"]"
        ]);

        $response->assertStatus(201);
    }
}
