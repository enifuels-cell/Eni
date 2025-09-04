<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Database\Seeder;

class ReferralTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users (excluding admins)
        $users = User::where('role', '!=', 'admin')->get();
        
        if ($users->count() < 2) {
            echo "Not enough users to create referrals. Need at least 2 non-admin users.\n";
            return;
        }
        
        // Create some test referrals
        $referrals = [
            // Kimmy Olis referred Test Client
            [
                'referrer_email' => 'dr.cedricplaza25@gmail.com',
                'referee_email' => 'user@test.com',
                'referral_code' => 'KIMMY2025'
            ],
            // Test Client referred Wilmer Pasco  
            [
                'referrer_email' => 'user@test.com',
                'referee_email' => 'dycinne@gmail.com',
                'referral_code' => 'TESTCLIENT01'
            ],
            // Kimmy Olis also referred Emily Test
            [
                'referrer_email' => 'dr.cedricplaza25@gmail.com',
                'referee_email' => 'emily@test.com',
                'referral_code' => 'KIMMY2025'
            ]
        ];
        
        foreach ($referrals as $referralData) {
            $referrer = User::where('email', $referralData['referrer_email'])->first();
            $referee = User::where('email', $referralData['referee_email'])->first();
            
            if ($referrer && $referee) {
                // Check if referral already exists
                $existingReferral = Referral::where('referrer_id', $referrer->id)
                    ->where('referee_id', $referee->id)
                    ->first();
                    
                if (!$existingReferral) {
                    Referral::create([
                        'referrer_id' => $referrer->id,
                        'referee_id' => $referee->id,
                        'referral_code' => $referralData['referral_code'],
                        'referred_at' => now()->subDays(rand(1, 30))
                    ]);
                    
                    echo "Created referral: {$referrer->name} -> {$referee->name}\n";
                }
            }
        }
        
        echo "Referral test data seeding completed!\n";
    }
}
