<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ExpiredEmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_verification_link_fails(): void
    {
        $user = User::factory()->unverified()->create();

        // Generate an already expired signed URL (set expiry in the past)
        $expiredUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(5), // already expired
            [
                'id' => $user->id,
                'hash' => sha1($user->email)
            ]
        );

        $response = $this->actingAs($user)->get($expiredUrl);

        $response->assertStatus(403); // Invalid or expired signature => 403 by framework
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
