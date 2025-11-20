<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestmentPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_amount',
        'max_amount',
        'daily_shares_rate',
        'effective_days',
        'available_slots',
        'referral_bonus_rate',
        'active',
        'image'
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'daily_shares_rate' => 'decimal:2',
        'referral_bonus_rate' => 'decimal:2',
        'active' => 'boolean'
    ];

    // Relationships
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    // Scopes
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

    // Attribute aliases
    public function getMinimumAmountAttribute() { return $this->min_amount; }
    public function getMaximumAmountAttribute() { return $this->max_amount; }
    public function getInterestRateAttribute() { return $this->daily_shares_rate; }
    public function getDurationDaysAttribute() { return $this->effective_days; }
    public function getIsActiveAttribute() { return $this->active; }
}
