<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $referral_id
 * @property int $investment_id
 * @property numeric $bonus_amount
 * @property bool $paid
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Investment $investment
 * @property-read \App\Models\Referral $referral
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus paid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus whereBonusAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus whereInvestmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus whereReferralId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReferralBonus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReferralBonus extends Model
{
    protected $fillable = [
        'referral_id',
        'investment_id',
        'bonus_amount',
        'paid',
        'paid_at'
    ];

    protected $casts = [
        'bonus_amount' => \App\Casts\MoneyCast::class,
        'paid' => 'boolean',
        'paid_at' => 'datetime'
    ];

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    public function scopePending($query)
    {
        return $query->where('paid', false);
    }
}
