<?php

namespace App\Observers;

use App\Models\Investment;
use App\Services\NotificationService;

class InvestmentObserver
{
    public function created(Investment $investment): void
    {
        // Create investment notification when investment is made
        NotificationService::createInvestmentNotification(
            $investment->user,
            'Investment Created',
            "You've successfully invested $" . number_format($investment->amount, 2) . " in " . $investment->package->name . "."
        );
    }

    public function updated(Investment $investment): void
    {
        // Create notification when investment status changes
        if ($investment->wasChanged('status')) {
            $message = match($investment->status) {
                'active' => "Your investment in {$investment->package->name} is now active and earning returns.",
                'completed' => "Your investment in {$investment->package->name} has matured. Check your balance for returns.",
                'cancelled' => "Your investment in {$investment->package->name} has been cancelled.",
                default => "Your investment status has been updated to {$investment->status}."
            };

            NotificationService::createInvestmentNotification(
                $investment->user,
                'Investment Status Updated',
                $message
            );
        }
    }
}
