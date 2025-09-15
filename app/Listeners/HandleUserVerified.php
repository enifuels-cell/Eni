<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleUserVerified implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 1;

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;
        Log::channel('investment')->info('User email verified', [
            'user_id' => $user->id,
            'email' => $user->email,
            'verified_at' => now()->toIso8601String(),
        ]);

        \App\Services\AuditLogger::log($user instanceof \Illuminate\Contracts\Auth\Authenticatable ? $user : null, 'user.verified_email', $user, [
            'email' => $user->email,
        ]);

        // Optional: place to award sign-up bonuses, send welcome mail, etc.
    }
}
