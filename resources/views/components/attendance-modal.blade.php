<!-- Attendance Modal Component -->
<div id="attendance-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gradient-to-br from-blue-900/90 to-purple-900/90 backdrop-blur-xl border border-white/20 rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="bg-gradient-to-r from-eni-yellow/20 to-yellow-400/20 p-6 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Daily Attendance</h2>
                            <p class="text-white/70">You've earned a raffle ticket for the iPhone Air! 📱🎫</p>
                        </div>
                    </div>
                    <button onclick="closeAttendanceModal()" class="text-white/60 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)]">
                <!-- Current Month Stats -->
                <div class="bg-black/20 rounded-2xl p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-eni-yellow mb-2">{{ $currentMonthTickets }}</div>
                            <div class="text-white/70">Tickets This Month</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-400 mb-2">{{ $currentMonthAttendance }}</div>
                            <div class="text-white/70">Days Logged In</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-400 mb-2">{{ $currentMonthDays }}</div>
                            <div class="text-white/70">Days in Month</div>
                        </div>
                    </div>
                </div>

                <!-- Calendar View -->
                <div class="bg-black/20 rounded-2xl p-6 mb-6">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ \Carbon\Carbon::now()->format('F Y') }} Attendance
                    </h3>

                    <div class="grid grid-cols-7 gap-2 mb-4">
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="text-center text-sm font-medium text-white/60 py-2">{{ $day }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $currentMonth = \Carbon\Carbon::now();
                            $startOfMonth = $currentMonth->copy()->startOfMonth();
                            $endOfMonth = $currentMonth->copy()->endOfMonth();
                            $startDayOfWeek = $startOfMonth->dayOfWeek;

                            // Add empty cells for days before the start of the month
                            for ($i = 0; $i < $startDayOfWeek; $i++) {
                                echo '<div class="aspect-square"></div>';
                            }

                            // Loop through each day of the month
                            for ($day = 1; $day <= $endOfMonth->day; $day++) {
                                $date = $currentMonth->copy()->day($day);
                                $isToday = $date->isToday();
                                $hasAttendance = in_array($date->toDateString(), $attendanceDates);
                                $isFuture = $date->isFuture();
                        @endphp

                        <div class="aspect-square rounded-xl border-2 {{ $isToday ? 'border-eni-yellow bg-eni-yellow/10' : ($hasAttendance ? 'border-green-400 bg-green-400/20' : ($isFuture ? 'border-white/10 bg-black/10' : 'border-red-400/20 bg-red-400/10 hover:border-blue-400 hover:bg-blue-400/10 cursor-pointer')) }} flex items-center justify-center text-sm font-medium transition-all hover:scale-105"
                             @if(!$isFuture && !$hasAttendance && !$isToday)
                                 data-date="{{ $date->format('Y-m-d') }}"
                                 data-clickable="true"
                                 title="Click to mark attendance for {{ $date->format('M j, Y') }}"
                             @endif>
                            @if($hasAttendance)
                                <div class="text-center">
                                    <div class="text-white font-bold">{{ $day }}</div>
                                    <div class="text-xs text-green-400">✓</div>
                                </div>
                            @elseif($isFuture)
                                <div class="text-white/40">{{ $day }}</div>
                            @else
                                <div class="text-center">
                                    <div class="text-red-400 font-bold">{{ $day }}</div>
                                    <div class="text-xs text-red-400/60">Click to mark</div>
                                </div>
                            @endif
                        </div>

                        @php
                            }
                        @endphp
                    </div>
                </div>

                <!-- Raffle Info -->
                <div class="bg-gradient-to-r from-purple-900/30 to-pink-900/30 rounded-2xl p-6">
                    <!-- Prize Image -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/iPhone-17-Air.jpg') }}"
                             alt="iPhone Air Prize"
                             class="w-32 h-32 object-contain mx-auto rounded-xl shadow-lg border-2 border-eni-yellow/30"
                             onerror="this.style.display='none'">
                        <div class="mt-2">
                            <div class="text-2xl font-bold text-eni-yellow">🏆 iPhone Air</div>
                            <div class="text-sm text-white/60">Monthly Grand Prize</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white">Monthly iPhone Air Raffle</h3>
                    </div>

                    <p class="text-white/80 mb-4">
                        🎯 <strong>Login daily to earn raffle tickets!</strong> At the end of each month, we'll randomly select a winner
                        based on the number of tickets earned. More consecutive logins = more chances to win the iPhone Air!
                    </p>

                    <div class="bg-gradient-to-r from-eni-yellow/10 to-yellow-400/10 rounded-xl p-4 border border-eni-yellow/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-white/60">Next Draw</div>
                                <div class="text-white font-semibold">{{ \Carbon\Carbon::now()->endOfMonth()->format('M j, Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-white/60">Your Tickets</div>
                                <div class="text-eni-yellow font-bold text-xl">{{ $currentMonthTickets }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Motivational Message -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-white/70 italic">
                            💪 Keep logging in daily to boost your chances of winning the iPhone Air!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-black/20 p-6 border-t border-white/10">
                <div class="flex justify-between items-center">
                    <div class="text-white/60 text-sm">
                        🎯 Keep logging in daily to increase your chances of winning the iPhone Air!
                    </div>
                    <button onclick="closeAttendanceModal()" class="bg-eni-yellow text-black px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Attendance Modal Functions
function showAttendanceModal() {
    document.getElementById('attendance-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAttendanceModal() {
    document.getElementById('attendance-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';

    // Mark modal as shown for today
    localStorage.setItem('attendance_modal_shown_' + new Date().toDateString(), 'true');
}

// Handle date clicks for manual attendance marking
function markAttendance(date, element) {
    if (confirm(`Mark attendance for ${new Date(date).toLocaleDateString()}?`)) {
        // Show loading state
        element.style.opacity = '0.6';
        element.style.pointerEvents = 'none';

        // Send request to mark attendance
        fetch('/dashboard/mark-attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ date: date })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the UI to show attendance marked
                element.className = element.className.replace('border-red-400/20 bg-red-400/10', 'border-green-400 bg-green-400/20');
                element.innerHTML = `
                    <div class="text-center">
                        <div class="text-white font-bold">${new Date(date).getDate()}</div>
                        <div class="text-xs text-green-400">✓</div>
                    </div>
                `;

                // Update ticket count if provided
                if (data.newTicketCount) {
                    const ticketElements = document.querySelectorAll('.ticket-count');
                    ticketElements.forEach(el => el.textContent = data.newTicketCount);
                }

                // Show success message with raffle promo
                showNotification('🎫 Attendance marked! You\'re now entered in the iPhone Air raffle! 📱', 'success');
            } else {
                showNotification(data.message || 'Failed to mark attendance', 'error');
                // Restore original state
                element.style.opacity = '1';
                element.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            // Restore original state
            element.style.opacity = '1';
            element.style.pointerEvents = 'auto';
        });
    }
}

// Simple notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add click event listeners to clickable dates
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-clickable="true"]').forEach(element => {
        element.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            if (date) {
                markAttendance(date, this);
            }
        });
    });
});
</script>

@if(isset($showModal) && $showModal)
<script>
// Auto-show modal if user hasn't seen it today
document.addEventListener('DOMContentLoaded', function() {
    const modalShownToday = localStorage.getItem('attendance_modal_shown_' + new Date().toDateString());
    if (!modalShownToday) {
        setTimeout(() => showAttendanceModal(), 1000); // Show after 1 second
    }
});
</script>
@endif