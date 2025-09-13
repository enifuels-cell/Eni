<?php

namespace Database\Factories;

use App\Models\InvestmentPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<InvestmentPackage> */
class InvestmentPackageFactory extends Factory
{
    protected $model = InvestmentPackage::class;

    public function definition(): array
    {
        return [
            'name' => 'Package '.strtoupper($this->faker->lexify('???')),
            'min_amount' => 100,
            'max_amount' => 10000,
            'daily_shares_rate' => 1.50,
            'effective_days' => 30,
            'available_slots' => null,
            'referral_bonus_rate' => 5.00,
            'active' => true,
        ];
    }
}
