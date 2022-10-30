<?php

namespace Tests\Feature;

use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
