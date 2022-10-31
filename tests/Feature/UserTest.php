<?php

namespace Tests\Feature;

use App\Mail\VerifyEmail;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Notifications\UserVerifyNotification;

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
        $email = 'john.doe@example.com';
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

    /** @test */
    public function a_confirmation_email_must_be_sent_after_registration()
    {
        $email = 'john.doe@example.com';
        $password = Hash::make('password');

        $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $user = User::where('email', $email)->first();

        $notification = new class extends UserVerifyNotification {
            public function publicVerificationUrl($user) {
                return parent::verificationUrl($user);
            }
        };

        $verificationUrl = $notification->publicVerificationUrl($user);

        $email = new VerifyEmail($user, $verificationUrl);
        $email->assertHasSubject('Confirm your '.config('app.name').' account, '.$user->first_name);
        $email->assertSeeInText($user->username);
        $email->assertSeeInText($verificationUrl);

        Mail::fake();
        Mail::to($user->email)->send($email);

        Mail::assertSent(VerifyEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
