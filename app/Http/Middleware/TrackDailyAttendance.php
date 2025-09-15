<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyAttendance;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class TrackDailyAttendance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track attendance for authenticated users
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user has already logged in today
            if (!$user->hasLoggedInToday()) {
                // Record today's attendance
                $attendance = $user->recordTodaysAttendance();

                // Update user's last login timestamp
                $user->update([
                    'last_login_at' => Carbon::now(),
                    'last_login_ip' => $request->ip(),
                ]);

                // Store attendance info in session for modal display
                session([
                    'attendance_recorded' => true,
                    'attendance_ticket_earned' => true,
                    'attendance_date' => $attendance->attendance_date->toDateString(),
                ]);
            }
        }

        return $next($request);
    }
}
