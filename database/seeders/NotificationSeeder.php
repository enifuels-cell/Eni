<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\NotificationService;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            // Create welcome notification for new users
            NotificationService::createWelcomeNotification($user);
            
            // Create account verified notification
            NotificationService::createAccountVerifiedNotification($user);
            
            // Create investment package notification
            NotificationService::createInvestmentPackageNotification($user);
            
            // Create PIN setup notification if PIN is not set
            if (!$user->pin_hash) {
                NotificationService::createPinSetupNotification($user);
            }
            
            // Create some sample notifications
            NotificationService::create($user, [
                'title' => 'Investment Matured',
                'message' => 'Your Energy Saver investment has successfully matured. Check your balance.',
                'category' => 'investment',
                'type' => 'success',
                'priority' => 'high'
            ]);
            
            NotificationService::create($user, [
                'title' => 'Referral Bonus Earned',
                'message' => 'You earned $50 referral bonus from a new signup.',
                'category' => 'referral',
                'type' => 'success',
                'priority' => 'medium'
            ]);
            
            NotificationService::create($user, [
                'title' => 'Withdrawal Processed',
                'message' => 'Your withdrawal request of $500 has been processed.',
                'category' => 'transaction',
                'type' => 'info',
                'priority' => 'medium'
            ]);
        }
    }
}
