<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\User\DashboardController;

class SimulateDeposit extends Command
{
    protected $signature = 'simulate:deposit {user_id} {package_id} {amount}';
    protected $description = 'Simulate a bank_transfer deposit to create a pending investment';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $packageId = $this->argument('package_id');
        $amount = $this->argument('amount');

        $controller = new DashboardController();

        $request = Request::create('/deposit', 'POST', [
            'payment_method' => 'bank_transfer',
            'package_id' => $packageId,
            'amount' => $amount,
            'user_id' => $userId,
        ]);

        try {
            $response = $controller->processDeposit($request);
            $this->info('Simulated deposit processed. Response: ' . json_encode($response));
        } catch (\Exception $e) {
            $this->error('Error during simulation: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
