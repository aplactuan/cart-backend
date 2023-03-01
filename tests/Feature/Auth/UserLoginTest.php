<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    public function test_it_requires_an_email_when_logging_in()
    {
        $this->json('POST', '/api/auth/login')
            ->assertJsonValidationErrors('email');
    }

    public function test_it_requires_a_password_when_logging_in()
    {
        $this->json('POST', '/api/auth/login')
            ->assertJsonValidationErrors('password');
    }

    public function test_it_returns_an_error_when_password_did_not_match()
    {
        $user = User::factory()->create([
            'password' => 'secret'
        ]);

        $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'notSecret'
        ])->assertJsonPath('errors.email.0', 'Invalid Credentials');
    }

    public function test_it_returns_a_token_when_credential_is_correct()
    {
        $user = User::factory()->create([
            'password' => 'secret'
        ]);

        $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
    }
}
