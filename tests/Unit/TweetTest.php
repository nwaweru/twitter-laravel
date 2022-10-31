<?php

namespace Tests\Unit;

use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tweet_url_must_not_be_null()
    {
        $tweet = Tweet::factory()->create();

        $this->assertNotNull($tweet->url);
    }

    /** @test */
    public function a_tweet_url_must_in_correct_format()
    {
        $tweet = Tweet::factory()->create();

        $url = url($tweet->owner->username.'/tweet/'.$tweet->id);

        $this->assertEquals($url, $tweet->url);
    }
}
