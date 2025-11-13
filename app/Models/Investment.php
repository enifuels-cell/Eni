<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'amount' => 'decimal:2',
        'daily_shares_rate' => 'decimal:2',
        'total_interest_earned' => 'decimal:2',
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

    public function calculateDailyInterest()
    {
        $totalAmount = (float)$this->amount;
        $interestRate = $this->investmentPackage->daily_shares_rate / 100;

        return $totalAmount * $interestRate;
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

    // The createReferralBonus() method was removed here as the logic is handled
    // correctly within the InvestmentService.php.
}
