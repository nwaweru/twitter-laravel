<?php

namespace Tests\Feature;

use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_a_tweet()
    {
        $tweet = Tweet::factory()->create();

        $response = $this->get($tweet->url);

        $response->assertSee($tweet->body);
    }

    /** @test */
    public function a_user_can_register()
    {
        $email = 'johndoe@example.com';
        $password = Hash::make('password');

        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

        $response->assertRedirect('/home');
    }
}
