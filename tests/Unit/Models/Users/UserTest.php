<?php

namespace Tests\Unit\Models\Users;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_hash_the_password()
    {
        $user = User::factory()->create([
            'password' => 'cat'
        ]);

        $this->assertNotEquals('cat', $user->password);
    }
}
