<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Login_SuccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @testWith ["member@gmail.com", "right"]
     *           ["admin@gmail.com", "right"]
     */
    public function test_login_right_user_pass_returns_logged_in($mail, $pass)
    {
        // GIVEN
        User::create([
            'name' => 'Name',
            'email' => $mail,
            'password' => bcrypt($pass),
            'api_token' => 'token123'
        ]);

        // WHEN
        $response = $this->post('/api/login', ['email' => $mail, 'password' => $pass]);

        // THEN
        $response->assertStatus(200);
        $response->assertExactJson([
            'success' => 'OK',
            'token' => 'token123'
        ]);
    }
}
