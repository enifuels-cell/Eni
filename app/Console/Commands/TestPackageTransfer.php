<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\InvestmentPackage;
use Illuminate\Support\Facades\DB;

class TestPackageTransfer extends Command
{
    protected $signature = 'test:package-transfer {from_email} {to_email} {amount} {package_id}';
    protected $description = 'Test package transfer functionality';

    public function handle()
    {
        $fromEmail = $this->argument('from_email');
        $toEmail = $this->argument('to_email');
        $amount = (float) $this->argument('amount');
        $packageId = (int) $this->argument('package_id');
        
        $sender = User::where('email', $fromEmail)->first();
        $recipient = User::where('email', $toEmail)->first();
        $package = InvestmentPackage::find($packageId);
        
        if (!$sender) {
            $this->error('Sender not found');
            return;
        }
        
        if (!$recipient) {
            $this->error('Recipient not found');
            return;
        }
        
        if (!$package) {
            $this->error('Package not found');
            return;
        }
        
        $this->info("Testing package transfer:");
        $this->info("From: {$sender->email}");
        $this->info("To: {$recipient->email}");
        $this->info("Amount: $" . number_format($amount, 2));
        $this->info("Package: {$package->name}");
        $this->info("Package range: $" . number_format($package->min_amount, 2) . " - $" . number_format($package->max_amount, 2));
        
        $this->info("Before transfer:");
        $this->info("Sender balance: $" . number_format($sender->account_balance, 2));
        $this->info("Recipient balance: $" . number_format($recipient->account_balance, 2));
        $this->info("Recipient investments: " . $recipient->investments()->count());
        
        // Validate amount
        if ($amount < $package->min_amount || $amount > $package->max_amount) {
            $this->error("Amount not in package range!");
            return;
        }
        
        try {
            DB::transaction(function () use ($sender, $recipient, $amount, $package) {
                // Deduct from sender
                $sender->decrement('account_balance', $amount);
                
                // Add to recipient
                $recipient->increment('account_balance', $amount);

                // Record sender transaction
                $sender->transactions()->create([
                    'type' => 'transfer',
                    'amount' => -$amount,
                    'status' => 'completed',
                    'description' => 'Package transfer to ' . $recipient->email . ' (' . $package->name . ')',
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);

                // Record recipient transaction
                $recipient->transactions()->create([
                    'type' => 'transfer',
                    'amount' => $amount,
                    'status' => 'completed',
                    'description' => 'Package transfer from ' . $sender->email . ' (' . $package->name . ')',
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);

                // Create investment for recipient
                $investment = $recipient->investments()->create([
                    'investment_package_id' => $package->id,
                    'amount' => $amount,
                    'daily_shares_rate' => $package->daily_shares_rate,
                    'remaining_days' => $package->effective_days,
                    'total_interest_earned' => 0,
                    'active' => true,
                    'started_at' => now(),
                    'ended_at' => null,
                ]);

                // Create investment transaction record for recipient
                $recipient->transactions()->create([
                    'type' => 'other',
                    'amount' => -$amount,
                    'status' => 'completed',
                    'description' => 'Investment in ' . $package->name . ' (funded by transfer)',
                    'reference' => 'INV' . time() . rand(1000, 9999)
                ]);

                // Deduct investment amount from recipient's balance
                $recipient->decrement('account_balance', $amount);
                
                // Update package slots if applicable
                if ($package->available_slots !== null) {
                    $package->decrement('available_slots');
                }
            });
            
            $sender->refresh();
            $recipient->refresh();
            
            $this->info("Package transfer completed successfully!");
            $this->info("After transfer:");
            $this->info("Sender balance: $" . number_format($sender->account_balance, 2));
            $this->info("Recipient balance: $" . number_format($recipient->account_balance, 2));
            $this->info("Recipient investments: " . $recipient->investments()->count());
            
            $latestInvestment = $recipient->investments()->latest()->first();
            $this->info("Latest investment details:");
            $this->info("- Package: {$latestInvestment->investmentPackage->name}");
            $this->info("- Amount: $" . number_format($latestInvestment->amount, 2));
            $this->info("- Daily rate: {$latestInvestment->daily_shares_rate}%");
            $this->info("- Remaining days: {$latestInvestment->remaining_days}");
            $this->info("- Status: " . ($latestInvestment->active ? 'Active' : 'Inactive'));
            
        } catch (\Exception $e) {
            $this->error("Package transfer failed: " . $e->getMessage());
            $this->error("Error details: " . $e->getFile() . ':' . $e->getLine());
        }
    }
}
