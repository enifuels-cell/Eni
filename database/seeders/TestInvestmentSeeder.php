<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Investment;
use App\Models\User;
use App\Models\InvestmentPackage;

class TestInvestmentSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $package = InvestmentPackage::first();

        if ($user && $package) {
            $investment = Investment::create([
                'user_id' => $user->id,
                'investment_package_id' => $package->id,
                'amount' => 1000.00,
                'daily_shares_rate' => $package->daily_shares_rate,
                'remaining_days' => 30,
                'total_interest_earned' => 0.00,
                'active' => true,
                'started_at' => now(),
            ]);

            echo "Created test investment #{$investment->id} for user {$user->name}\n";
            echo "Daily rate: {$package->daily_shares_rate}%\n";
            echo "Daily interest: $" . number_format($investment->calculateDailyInterest(), 2) . "\n";
        }
    }
}
