<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DailyAttendance extends Model
{
    protected $table = 'daily_attendance';
    
    protected $fillable = [
        'user_id',
        'attendance_date',
        'tickets_earned',
        'first_login_time',
        'logged_in_at'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'first_login_time' => 'datetime:H:i:s',
        'logged_in_at' => 'datetime',
    ];

    /**
     * Get the user that owns the attendance record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user has already logged in today
     */
    public static function hasLoggedInToday(int $userId): bool
    {
        return self::where('user_id', $userId)
            ->where('attendance_date', Carbon::today())
            ->exists();
    }

    /**
     * Record today's attendance for a user
     */
    public static function recordTodaysAttendance(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
            'attendance_date' => Carbon::today(),
            'tickets_earned' => 1,
            'first_login_time' => Carbon::now()->format('H:i:s'),
            'logged_in_at' => Carbon::now(),
        ]);
    }

    /**
     * Get user's attendance for current month
     */
    public static function getMonthlyAttendance(int $userId, ?Carbon $month = null): \Illuminate\Database\Eloquent\Collection
    {
        $month = $month ?? Carbon::now();
        
        return self::where('user_id', $userId)
            ->whereYear('attendance_date', $month->year)
            ->whereMonth('attendance_date', $month->month)
            ->orderBy('attendance_date')
            ->get();
    }

    /**
     * Get user's total tickets for current month
     */
    public static function getMonthlyTicketCount(int $userId, ?Carbon $month = null): int
    {
        $month = $month ?? Carbon::now();
        
        return self::where('user_id', $userId)
            ->whereYear('attendance_date', $month->year)
            ->whereMonth('attendance_date', $month->month)
            ->sum('tickets_earned');
    }
}
