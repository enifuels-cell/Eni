<?php

namespace Database\Factories;

use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Investment> */
class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition(): array
    {
        // For stress tests we want to avoid hitting unique name constraint on packages.
        $packageId = InvestmentPackage::query()->value('id') ?? InvestmentPackage::factory()->create()->id;

        return [
            'user_id' => User::factory(),
            'investment_package_id' => $packageId,
            'amount' => 1000,
            'daily_shares_rate' => 1.50,
            'remaining_days' => 30,
            'total_interest_earned' => 0,
            'active' => true,
            'started_at' => now()->subDay(),
            'ended_at' => null,
        ];
    }
}
