<?php

namespace Tests\Feature;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_email_sent_on_registration()
    {
        Mail::fake();

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '1234567890',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');

        Mail::assertQueued(WelcomeEmail::class, function ($mail) use ($userData) {
            return $mail->hasTo($userData['email']);
        });
    }

    public function test_welcome_email_with_referral()
    {
        Mail::fake();

        // Create a referrer user
        $referrer = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '1234567890',
            'referral_code' => $referrer->referral_code,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');

        Mail::assertQueued(WelcomeEmail::class, function ($mail) use ($userData) {
            return $mail->hasTo($userData['email']);
        });

        // Check if referral relationship was created
        $user = User::where('email', $userData['email'])->first();
        $this->assertDatabaseHas('referrals', [
            'referrer_id' => $referrer->id,
            'referee_id' => $user->id,
        ]);
    }

    public function test_welcome_email_content()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $mail = new WelcomeEmail($user);

        $rendered = $mail->render();

        $this->assertStringContainsString('Welcome to ENI Investment Platform', $rendered);
        $this->assertStringContainsString($user->name, $rendered);
        $this->assertStringContainsString('Thank you for choosing ENI Investment Platform', $rendered);
    }
}
