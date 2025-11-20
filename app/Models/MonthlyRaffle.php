<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Exception;

class MonthlyRaffle extends Model
{
    protected $fillable = [
        'title',
        'description',
        'raffle_year',
        'raffle_month',
        'status',
        'winner_user_id',
        'drawn_at',
        'draw_details'
    ];

    protected $casts = [
        'drawn_at' => 'datetime',
        'draw_details' => 'array',
    ];

    // Default attributes when creating a new instance
    protected $attributes = [
        'title' => 'Monthly iPhone Air Raffle',
        'description' => 'Win a brand new iPhone Air! Login daily to earn tickets and increase your chances!',
        'status' => 'active',
    ];

    /**
     * The winner of this raffle
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    /**
     * Get current month's raffle, or null if it doesn't exist
     */
    public static function getCurrentMonthRaffle(): ?self
    {
        $now = Carbon::now();
        return self::where('raffle_year', $now->year)
            ->where('raffle_month', $now->month)
            ->first();
    }

    /**
     * Ensure a raffle exists for the current month, create if missing
     */
    public static function ensureCurrentMonthRaffle(): self
    {
        $now = Carbon::now();

        return self::firstOrCreate(
            [
                'raffle_year' => $now->year,
                'raffle_month' => $now->month,
            ],
            [
                'title' => 'Monthly iPhone Air Raffle - ' . $now->format('F Y'),
                'description' => 'Win a brand new iPhone Air! Login daily to earn tickets and increase your chances!',
                'status' => 'active',
            ]
        );
    }

    /**
     * Get all eligible users for this raffle with their ticket counts
     */
    public function getEligibleUsers(): Collection
    {
        return User::select('users.*')
            ->selectRaw('COALESCE(SUM(daily_attendance.tickets_earned), 0) AS total_tickets')
            ->leftJoin('daily_attendance', function ($join) {
                $join->on('users.id', '=', 'daily_attendance.user_id')
                     ->whereYear('daily_attendance.attendance_date', $this->raffle_year)
                     ->whereMonth('daily_attendance.attendance_date', $this->raffle_month);
            })
            ->groupBy('users.id')
            ->having('total_tickets', '>', 0)
            ->orderByDesc('total_tickets')
            ->get();
    }

    /**
     * Conduct the raffle draw and select a winner
     *
     * @throws Exception if no eligible users exist
     */
    public function conductDraw(): User
    {
        $eligibleUsers = $this->getEligibleUsers();

        if ($eligibleUsers->isEmpty()) {
            throw new Exception('No eligible users for this raffle.');
        }

        // Build a weighted array based on tickets
        $weightedUsers = [];
        foreach ($eligibleUsers as $user) {
            for ($i = 0; $i < $user->total_tickets; $i++) {
                $weightedUsers[] = $user;
            }
        }

        // Randomly pick a winner
        $winner = $weightedUsers[array_rand($weightedUsers)];

        // Update raffle record with winner and draw details
        $this->update([
            'status' => 'drawn',
            'winner_user_id' => $winner->id,
            'drawn_at' => Carbon::now(),
            'draw_details' => [
                'total_participants' => $eligibleUsers->count(),
                'total_tickets' => $eligibleUsers->sum('total_tickets'),
                'winner_tickets' => $winner->total_tickets,
                'draw_method' => 'weighted_random',
            ],
        ]);

        return $winner;
    }

    /**
     * Check if the raffle has already been drawn
     */
    public function isDrawn(): bool
    {
        return $this->status === 'drawn' && !is_null($this->winner_user_id);
    }

    /**
     * Get human-readable raffle period
     */
    public function getPeriodAttribute(): string
    {
        return Carbon::createFromDate($this->raffle_year, $this->raffle_month, 1)
            ->format('F Y');
    }
}
