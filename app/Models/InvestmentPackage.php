<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property numeric $min_amount
 * @property numeric $max_amount
 * @property numeric $daily_shares_rate
 * @property int $effective_days
 * @property int|null $available_slots
 * @property numeric $referral_bonus_rate
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $duration_days
 * @property-read mixed $interest_rate
 * @property-read mixed $is_active
 * @property-read mixed $maximum_amount
 * @property-read mixed $minimum_amount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Investment> $investments
 * @property-read int|null $investments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereAvailableSlots($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereDailySharesRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereEffectiveDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereMaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereReferralBonusRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InvestmentPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvestmentPackage extends Model
{
    protected $fillable = [
        'name',
        'min_amount',
        'max_amount',
        'daily_shares_rate',
        'effective_days',
        'available_slots',
        'referral_bonus_rate',
        'active'
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'daily_shares_rate' => 'decimal:2',
        'referral_bonus_rate' => 'decimal:2',
        'active' => 'boolean'
    ];

    // Alias attributes for backwards compatibility
    public function getMinimumAmountAttribute()
    {
        return $this->min_amount;
    }

    public function getMaximumAmountAttribute()
    {
        return $this->max_amount;
    }

    public function getInterestRateAttribute()
    {
        return $this->daily_shares_rate;
    }

    public function getDurationDaysAttribute()
    {
        return $this->effective_days;
    }

    public function getIsActiveAttribute()
    {
        return $this->active;
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('active', true)
                    ->where(function($q) {
                        $q->whereNull('available_slots')
                          ->orWhere('available_slots', '>', 0);
                    });
    }
}
