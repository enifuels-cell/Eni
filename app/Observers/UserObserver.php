<?php

namespace App\Observers;

use App\Models\User;
use App\Services\NotificationService;

class UserObserver
{
    public function created(User $user): void
    {
        // Create welcome notification when user is created
        NotificationService::createWelcomeNotification($user);
    }

    public function updated(User $user): void
    {
        // Create account verified notification when email is verified
        if ($user->wasChanged('email_verified_at') && $user->email_verified_at) {
            NotificationService::createAccountVerifiedNotification($user);
        }

        // Create PIN setup notification when PIN is set
        if ($user->wasChanged('pin_hash') && $user->pin_hash) {
            NotificationService::create($user, [
                'title' => 'PIN Login Enabled',
                'message' => 'Your 4-digit PIN login has been successfully enabled for secure access.',
                'category' => 'security',
                'type' => 'success',
                'priority' => 'medium'
            ]);
        }
    }
}
