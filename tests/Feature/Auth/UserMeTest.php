<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserMeTest extends TestCase
{
    public function test_it_returns_unauthorized_status_when_not_login()
    {
        $this->json('GET', '/api/auth/me')
            ->assertStatus(401);
    }

    public function test_it_returns_user_details_when_login()
    {
        Passport::actingAs(
            $user = User::factory()->create()
        );

        $this->json('GET', '/api/auth/me')
            ->assertJsonFragment([
                'email' => $user->email
            ]);
    }
}
