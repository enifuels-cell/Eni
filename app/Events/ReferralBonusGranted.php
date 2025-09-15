<?php

namespace App\Events;

use App\Models\ReferralBonus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReferralBonusGranted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ReferralBonus $referralBonus)
    {
    }
}
