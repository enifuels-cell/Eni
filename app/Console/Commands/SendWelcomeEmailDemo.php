<?php

namespace App\Console\Commands;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:welcome-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a demo welcome email to test the email template';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Create a demo user for testing
        $user = new User([
            'name' => 'Demo User',
            'email' => $email,
            'referral_code' => 'DEMO1234',
        ]);
        
        // Optional: Create a referrer for demo
        $referrer = new User([
            'name' => 'John Referrer',
            'email' => 'referrer@example.com',
            'referral_code' => 'REF5678',
        ]);
        
        // Send the welcome email
        try {
            Mail::to($email)->send(new WelcomeEmail($user, $referrer));
            $this->info("✅ Welcome email successfully sent to: {$email}");
            $this->info("📧 Please check your email inbox and spam folder.");
            $this->info("🎨 The email includes ENI corporate branding and referral acknowledgment.");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send welcome email: " . $e->getMessage());
            $this->info("💡 Make sure Gmail SMTP settings are configured in .env file");
        }
    }
}
