<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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

    /**
     * Get the winner of this raffle.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    /**
     * Get current month's raffle
     */
    public static function getCurrentMonthRaffle(): ?self
    {
        $now = Carbon::now();
        
        return self::where('raffle_year', $now->year)
            ->where('raffle_month', $now->month)
            ->first();
    }

    /**
     * Create raffle for current month if it doesn't exist
     */
    public static function ensureCurrentMonthRaffle(): self
    {
        $now = Carbon::now();
        
        return self::firstOrCreate([
            'raffle_year' => $now->year,
            'raffle_month' => $now->month,
        ], [
            'title' => 'Monthly iPhone Air Raffle - ' . $now->format('F Y'),
            'description' => 'Win a brand new iPhone Air! Login daily to earn tickets and increase your chances!',
            'status' => 'active',
        ]);
    }

    /**
     * Get all eligible users for this raffle with their ticket counts
     */
    public function getEligibleUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::select('users.*')
            ->selectRaw('COALESCE(SUM(daily_attendance.tickets_earned), 0) as total_tickets')
            ->leftJoin('daily_attendance', function($join) {
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
     * Conduct the raffle draw
     */
    public function conductDraw(): User
    {
        $eligibleUsers = $this->getEligibleUsers();
        
        if ($eligibleUsers->isEmpty()) {
            throw new \Exception('No eligible users for this raffle');
        }

        // Create weighted array based on tickets
        $weightedUsers = [];
        foreach ($eligibleUsers as $user) {
            for ($i = 0; $i < $user->total_tickets; $i++) {
                $weightedUsers[] = $user;
            }
        }

        // Random selection
        $winner = $weightedUsers[array_rand($weightedUsers)];
        
        // Update raffle with winner
        $this->update([
            'status' => 'drawn',
            'winner_user_id' => $winner->id,
            'drawn_at' => Carbon::now(),
            'draw_details' => [
                'total_participants' => $eligibleUsers->count(),
                'total_tickets' => $eligibleUsers->sum('total_tickets'),
                'winner_tickets' => $winner->total_tickets,
                'draw_method' => 'weighted_random',
            ]
        ]);

        return $winner;
    }
}
