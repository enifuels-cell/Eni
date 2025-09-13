<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $investment_id
 * @property numeric $interest_amount
 * @property \Illuminate\Support\Carbon $interest_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Investment $investment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog forDate($date)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog whereInterestAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog whereInterestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog whereInvestmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DailyInterestLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DailyInterestLog extends Model
{
    protected $fillable = [
        'investment_id',
        'interest_amount',
        'interest_date'
    ];

    protected $casts = [
        'interest_amount' => \App\Casts\MoneyCast::class,
        'interest_date' => 'date'
    ];

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('interest_date', $date);
    }
}
