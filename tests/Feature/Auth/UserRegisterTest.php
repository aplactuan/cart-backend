<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    public function test_it_requires_a_name()
    {
        $this->json('POST', '/api/auth/register', User::factory()->raw([
            'name' => ''
        ]))
            ->assertJsonValidationErrors('name');
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', '/api/auth/register', User::factory()->raw([
            'password' => ''
        ]))
            ->assertJsonValidationErrors('password');
    }

    public function test_it_requires_an_email()
    {
        $this->json('POST', '/api/auth/register', User::factory()->raw([
            'email' => ''
        ]))
            ->assertJsonValidationErrors('email');
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('POST', '/api/auth/register', User::factory()->raw([
            'email' => 'not_an_email'
        ]))
            ->assertJsonValidationErrors('email');
    }

    public function test_it_requires_a_unique_email()
    {
        $user = User::factory()->create([
            'email' => 'me@email.com'
        ]);

        $this->json('POST', '/api/auth/register', User::factory()->raw([
            'email' => $user->email
        ]))
            ->assertJsonValidationErrors('email');
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST', '/api/auth/register', $user = User::factory()->raw([
            'password' => 'cats'
        ]));

        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email']
        ]);
    }

    public function test_it_returns_a_json_data()
    {
        $this->json('POST', '/api/auth/register', $user = User::factory()->raw())
            ->assertJsonFragment([
                'name' => $user['name'],
                'email' => $user['email']
            ]);
    }
}
