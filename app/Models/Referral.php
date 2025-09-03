<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $referrer_id
 * @property int $referee_id
 * @property string $referral_code
 * @property \Illuminate\Support\Carbon|null $referred_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $referee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReferralBonus> $referralBonuses
 * @property-read int|null $referral_bonuses_count
 * @property-read \App\Models\User $referrer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereRefereeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereReferrerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Referral whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referee_id',
        'referral_code',
        'referred_at'
    ];

    protected $casts = [
        'referred_at' => 'datetime'
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function referralBonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class);
    }

    public function totalBonusEarned(): float
    {
        return $this->referralBonuses()->where('paid', true)->sum('bonus_amount');
    }
}
