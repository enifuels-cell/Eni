<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SignupBonusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'signup_bonus',
            'title' => 'Welcome Bonus Available!',
            'message' => 'Claim your $10 sign-up bonus now! This bonus will be added to your account balance.',
            'amount' => 10.00,
            'action_text' => 'Claim Bonus',
            'action_url' => route('user.claim-signup-bonus'),
            'icon' => 'gift',
            'color' => 'yellow',
        ];
    }
}
