<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\CanRegenerateAccessToken;

class UserLoginTest extends TestCase
{
    use CanRegenerateAccessToken;

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
        $this->createPersonalClient();

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

    public function test_it_returns_the_user_data_after_successfully_login()
    {
        $this->createPersonalClient();

        $user = User::factory()->create([
            'password' => 'secret'
        ]);

        $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertJsonFragment([
            'name' => $user->name
        ]);
    }
}
