<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_have_a_username()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->username);
    }
}
