<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Import the UserNotification model
        $userNotificationClass = \App\Models\UserNotification::class;
        $userClass = \App\Models\User::class;
        
        // Get all users
        $users = $userClass::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }

        foreach ($users as $user) {
            // Clear existing notifications for clean test
            $user->userNotifications()->delete();
            
            // Create sample notifications for each user
            $notifications = [
                [
                    'title' => 'Account Verified',
                    'message' => 'Your account has been successfully verified. You now have full access to all platform features.',
                    'category' => 'account',
                    'type' => 'success',
                    'priority' => 'medium',
                    'is_read' => true,
                    'created_at' => now()->subHours(24),
                ],
                [
                    'title' => 'New Investment Packages',
                    'message' => 'Check out our latest high-yield investment options. We\'ve updated our packages with improved returns and competitive daily interest rates.',
                    'category' => 'investment',
                    'type' => 'info',
                    'priority' => 'medium',
                    'action_url' => '/user/packages',
                    'is_read' => false,
                    'created_at' => now()->subHours(6),
                ],
                [
                    'title' => 'Investment Matured',
                    'message' => 'Your Energy Saver investment has successfully matured. Check your balance for the updated amount.',
                    'category' => 'investment',
                    'type' => 'success',
                    'priority' => 'high',
                    'is_read' => false,
                    'created_at' => now()->subHours(2),
                ],
                [
                    'title' => 'Referral Bonus Earned',
                    'message' => 'You earned $50 referral bonus from a new signup. Thank you for spreading the word!',
                    'category' => 'referral',
                    'type' => 'success',
                    'priority' => 'medium',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(30),
                ],
            ];

            foreach ($notifications as $notificationData) {
                $userNotificationClass::create([
                    'user_id' => $user->id,
                    ...$notificationData
                ]);
            }
        }

        $this->command->info('Sample notifications created for all users.');
    }
}
