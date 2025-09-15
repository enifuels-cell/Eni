<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Override the verification URL generation to allow longer expiration (default 60 min -> 24h here).
     */
    protected function verificationUrl($notifiable): string
    {
        $expiration = Carbon::now()->addHours(24);
        return URL::temporarySignedRoute(
            'verification.verify',
            $expiration,
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Customize the mail message.
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting('Welcome to Eni!')
            ->line('Please confirm your email address to activate your investment account.')
            ->action('Verify Email', $url)
            ->line('This link will expire in 24 hours. If it expires, you can request a new one from your dashboard.')
            ->line('If you did not create an account, no action is required.');
    }
}
