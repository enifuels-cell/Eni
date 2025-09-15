<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Transaction> */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'deposit',
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'reference' => $this->faker->uuid(),
            'status' => 'completed',
            'description' => $this->faker->sentence(),
            'receipt_path' => null,
            'processed_at' => now(),
        ];
    }

    public function pending(): self
    {
        return $this->state(fn () => ['status' => 'pending']);
    }
}
