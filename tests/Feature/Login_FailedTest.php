<?php

namespace Tests\Feature;

use Tests\TestCase;

class Login_FailedTest extends TestCase
{
    public function test_login_unsupported_method()
    {
        $response = $this->get('/api/login');

        $response->assertStatus(405);
    }

    /**
     * @testWith ["member@gmail.com", "wrong-pass"]
     *           ["admin@gmail.com", "wrong-pass"]
     *           ["wrong@gmail.com", "right"]
     */
    public function test_login_wrong_user_pass_returns_401($user, $pass)
    {
        // WHEN
        $response = $this->post('/api/login', ['email' => $user, 'password' => $pass]);

        // THEN
        $response->assertStatus(422);
        $response->assertExactJson(['message' => 'Wrong credentials provided.']);
        self::assertFalse($this->isAuthenticated());
    }
}
