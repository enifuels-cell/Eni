<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class DailyInterestLog extends Model
{
    protected $fillable = [
        'investment_id',
        'interest_amount',
        'interest_date'
    ];

    protected $casts = [
        'interest_amount' => 'decimal:2',
        'interest_date' => 'date'
    ];

    /**
     * Relationship to the Investment model.
     */
    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    /**
     * Relationship to the User model through Investment.
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,        // Final related model
            Investment::class,  // Intermediate model
            'id',               // Foreign key on Investment table
            'id',               // Foreign key on User table
            'investment_id',    // Local key on DailyInterestLog table
            'user_id'           // Local key on Investment table
        );
    }

    /**
     * Scope to filter logs by date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('interest_date', $date);
    }
}
