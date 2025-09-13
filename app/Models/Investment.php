<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $investment_package_id
 * @property numeric $amount
 * @property numeric $daily_shares_rate
 * @property int $remaining_days
 * @property numeric $total_interest_earned
 * @property bool $active
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyInterestLog> $dailyInterestLogs
 * @property-read int|null $daily_interest_logs_count
 * @property-read mixed $status
 * @property-read \App\Models\InvestmentPackage $investmentPackage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReferralBonus> $referralBonuses
 * @property-read int|null $referral_bonuses_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereDailySharesRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereInvestmentPackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereRemainingDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereTotalInterestEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Investment whereUserId($value)
 * @mixin \Eloquent
 */
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Investment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'investment_package_id',
        'amount',
        'daily_shares_rate',
        'remaining_days',
        'total_interest_earned',
        'active',
        'started_at',
        'ended_at',
        'investment_code'
    ];

    protected $casts = [
        'amount' => \App\Casts\MoneyCast::class,
        // Keep rate as decimal since it's a percentage, not an amount
        'daily_shares_rate' => 'decimal:2',
        'total_interest_earned' => \App\Casts\MoneyCast::class,
        'active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function investmentPackage(): BelongsTo
    {
        return $this->belongsTo(InvestmentPackage::class);
    }

    public function dailyInterestLogs(): HasMany
    {
        return $this->hasMany(DailyInterestLog::class);
    }

    public function referralBonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function calculateDailyInterest(): float
    {
        return $this->amount * ($this->daily_shares_rate / 100);
    }

    public function isExpired(): bool
    {
        return $this->remaining_days <= 0;
    }

    public function totalInterestEarned(): float
    {
        return $this->total_interest_earned ?? 0;
    }

    public function daysRemaining(): int
    {
        return max(0, $this->remaining_days);
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (!$model->investment_code) {
                $model->investment_code = static::generateInvestmentCode();
            }
        });
    }

    protected static function generateInvestmentCode(int $length = 6): string
    {
        $tries = 0; $code = '';
        do {
            $tries++;
            $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
            $segment = '';
            for ($i=0; $i<$length; $i++) {
                $segment .= $alphabet[random_int(0, strlen($alphabet)-1)];
            }
            $code = 'INV-' . $segment;
        } while (self::where('investment_code', $code)->exists() && $tries < 10);
        return $code;
    }

    // Alias for status
    public function getStatusAttribute()
    {
        if (!$this->active) {
            return 'inactive';
        }
        
        if ($this->remaining_days <= 0) {
            return 'completed';
        }
        
        return 'active';
    }

    /**
     * Create referral bonus when investment is activated
     */
    public function createReferralBonus(): void
    {
        // Check if the user was referred by someone
        $referral = $this->user->referralReceived;
        
        if (!$referral) {
            \Log::info('No referral found for user', ['user_id' => $this->user_id]);
            return;
        }

        // Check if referral bonus already exists for this investment
        $existingBonus = ReferralBonus::where('referral_id', $referral->id)
            ->where('investment_id', $this->id)
            ->first();
            
        if ($existingBonus) {
            \Log::info('Referral bonus already exists', ['investment_id' => $this->id]);
            return;
        }

        // Get the referral bonus rate from the investment package
        $package = $this->investmentPackage;
        $bonusRate = $package->referral_bonus_rate ?? 5; // Default 5% if not set
        $bonusAmount = $this->amount * ($bonusRate / 100);

        // Create the referral bonus record
        $referralBonus = ReferralBonus::create([
            'referral_id' => $referral->id,
            'investment_id' => $this->id,
            'bonus_amount' => $bonusAmount,
            'paid' => true,
            'paid_at' => now(),
        ]);

        // Credit the referrer's account
        $referrer = $referral->referrer;
        $referrer->increment('account_balance', $bonusAmount);

        // Create transaction record for the referrer
        try {
            \App\Models\Transaction::create([
                'user_id' => $referrer->id,
                'type' => 'referral_bonus',
                'amount' => $bonusAmount,
                'reference' => "Referral bonus for investment #{$this->id}",
                'status' => 'completed',
                'description' => "Referral bonus from {$this->user->name} ({$package->name} package)",
                'processed_at' => now(),
            ]);
            \Log::info('Referral bonus transaction created', ['referrer_id' => $referrer->id, 'amount' => $bonusAmount]);
        } catch (\Exception $e) {
            \Log::error('Failed to create referral bonus transaction', ['error' => $e->getMessage()]);
        }

        \Log::info('Referral bonus created', [
            'referrer_id' => $referrer->id,
            'referee_id' => $this->user_id,
            'investment_id' => $this->id,
            'bonus_amount' => $bonusAmount,
            'bonus_rate' => $bonusRate
        ]);
    }
}
