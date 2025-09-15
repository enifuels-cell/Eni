<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DailyAttendance;
use Carbon\Carbon;

// Test the markAttendance functionality
echo "Testing Manual Attendance Marking Feature\n";
echo "========================================\n\n";

// Simulate a request
$requestData = [
    'date' => Carbon::yesterday()->format('Y-m-d') // Test with yesterday's date
];

// Get a test user (assuming user with ID 1 exists)
$user = User::find(1);

if (!$user) {
    echo "❌ No test user found. Please create a user first.\n";
    exit(1);
}

echo "Test User: {$user->name} (ID: {$user->id})\n";
echo "Test Date: {$requestData['date']}\n\n";

// Check if attendance already exists
$existingAttendance = DailyAttendance::where('user_id', $user->id)
    ->where('attendance_date', $requestData['date'])
    ->first();

if ($existingAttendance) {
    echo "⚠️  Attendance already exists for this date. Skipping test.\n";
    exit(0);
}

// Record the attendance manually
try {
    $attendance = DailyAttendance::create([
        'user_id' => $user->id,
        'attendance_date' => Carbon::parse($requestData['date']),
        'tickets_earned' => 1,
        'first_login_time' => Carbon::now()->format('H:i:s'),
        'logged_in_at' => Carbon::now(),
    ]);

    echo "✅ Attendance marked successfully!\n";
    echo "   - Attendance ID: {$attendance->id}\n";
    echo "   - Tickets Earned: {$attendance->tickets_earned}\n";
    echo "   - Recorded At: {$attendance->logged_in_at}\n\n";

    // Get updated ticket count
    $newTicketCount = $user->getMonthlyTicketCount();
    echo "📊 Updated Monthly Ticket Count: {$newTicketCount}\n";

} catch (Exception $e) {
    echo "❌ Error marking attendance: {$e->getMessage()}\n";
}

echo "\nTest completed.\n";