<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\InvestmentPackage;
use Illuminate\Support\Facades\DB;

class TestTransfer extends Command
{
    protected $signature = 'test:transfer {from_email} {to_email} {amount}';
    protected $description = 'Test transfer functionality';

    public function handle()
    {
        $fromEmail = $this->argument('from_email');
        $toEmail = $this->argument('to_email');
        $amount = (float) $this->argument('amount');
        
        $sender = User::where('email', $fromEmail)->first();
        $recipient = User::where('email', $toEmail)->first();
        
        if (!$sender) {
            $this->error('Sender not found');
            return;
        }
        
        if (!$recipient) {
            $this->error('Recipient not found');
            return;
        }
        
        $this->info("Testing transfer from {$sender->email} to {$recipient->email}");
        $this->info("Amount: $" . number_format($amount, 2));
        $this->info("Sender balance before: $" . number_format($sender->account_balance, 2));
        $this->info("Recipient balance before: $" . number_format($recipient->account_balance, 2));
        
        try {
            DB::transaction(function () use ($sender, $recipient, $amount) {
                // Deduct from sender
                $sender->decrement('account_balance', $amount);
                
                // Add to recipient
                $recipient->increment('account_balance', $amount);

                // Record sender transaction
                $sender->transactions()->create([
                    'type' => 'transfer',
                    'amount' => -$amount, // Negative for outgoing transfer
                    'status' => 'completed',
                    'description' => 'Test transfer to ' . $recipient->email,
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);

                // Record recipient transaction
                $recipient->transactions()->create([
                    'type' => 'transfer',
                    'amount' => $amount, // Positive for incoming transfer
                    'status' => 'completed',
                    'description' => 'Test transfer from ' . $sender->email,
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);
            });
            
            $sender->refresh();
            $recipient->refresh();
            
            $this->info("Transfer completed successfully!");
            $this->info("Sender balance after: $" . number_format($sender->account_balance, 2));
            $this->info("Recipient balance after: $" . number_format($recipient->account_balance, 2));
            
        } catch (\Exception $e) {
            $this->error("Transfer failed: " . $e->getMessage());
            $this->error("Error details: " . $e->getFile() . ':' . $e->getLine());
        }
    }
}
