<?php

namespace App\Console\Commands;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality by sending a welcome email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Create a temporary user for testing
        $testUser = new User();
        $testUser->name = 'Test User';
        $testUser->email = $email;
        $testUser->created_at = now();
        
        try {
            $this->info('Sending test welcome email to: ' . $email);
            Mail::to($email)->send(new WelcomeEmail($testUser));
            $this->info('✅ Email sent successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
        }
    }
}
