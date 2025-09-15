<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    public function test_verification_link_marks_user_verified(): void
    {
        Notification::fake();
        Event::fake();

        $user = User::factory()->create(['email_verified_at' => null]);

        // Trigger notification
        $this->actingAs($user);
        $user->sendEmailVerificationNotification();
        Notification::assertSentTo($user, CustomVerifyEmail::class);

        // Build a fresh signed URL matching our custom 24h logic
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHour(), // shorter for test
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertRedirectContains('verified=1');

        $this->assertNotNull($user->fresh()->email_verified_at);
        Event::assertDispatched(Verified::class);
    }
}
