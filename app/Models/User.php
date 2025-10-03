<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Investment[] $investments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Referral[] $referralsGiven
 * @property-read \App\Models\Referral|null $referralReceived
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FranchiseApplication[] $franchiseApplications
 * @method float totalInvestedAmount()
 * @method float totalInterestEarned()
 * @method float totalReferralBonuses()
 * @method float accountBalance()
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $franchise_applications_count
 * @property-read int|null $investments_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $referrals_given_count
 * @property-read int|null $transactions_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'account_id',
        'account_balance',
        'signup_bonus_claimed',
        'signup_bonus_claimed_at',
        'last_login_at',
        'last_login_ip',
        'pin_hash',
        'pin_set_at',
        'bank_name',
        'account_number',
        'account_holder_name',
        'routing_number',
        'swift_code',
        'phone',
        'address',
        'suspended_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'pin_set_at' => 'datetime',
            'suspended_at' => 'datetime',
            'signup_bonus_claimed' => 'boolean',
            'signup_bonus_claimed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Investment Platform Relationships
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referralsGiven(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referralReceived(): HasOne
    {
        return $this->hasOne(Referral::class, 'referee_id');
    }

    public function franchiseApplications(): HasMany
    {
        return $this->hasMany(FranchiseApplication::class);
    }

    public function dailyInterestLogs(): HasMany
    {
        return $this->hasMany(DailyInterestLog::class);
    }

    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    // Attendance System Relationships
    public function dailyAttendance(): HasMany
    {
        return $this->hasMany(DailyAttendance::class);
    }

    public function raffleWins(): HasMany
    {
        return $this->hasMany(MonthlyRaffle::class, 'winner_user_id');
    }

    // Helper methods for investment platform
    public function totalInvestedAmount(): float
    {
        return (float)$this->investments()
            ->active()
            ->sum('amount');
    }

    public function totalInterestEarned(): float
    {
        return (float)$this->investments()
            ->active()
            ->sum('total_interest_earned');
    }

    public function totalReferralBonuses(): float
    {
        return (float)\App\Models\ReferralBonus::whereHas('referral', function($query) {
            $query->where('referrer_id', $this->id);
        })->where('paid', true)->sum('bonus_amount');
    }

    public function accountBalance(): float
    {
        // Simply return the account_balance column value
        // This column is properly maintained by increment/decrement operations:
        // - Incremented by: deposits, interest, referral bonuses
        // - Decremented by: withdrawals, investments from balance
        // Investment amounts are NOT included here (they're locked in totalInvestedAmount)
        return (float)$this->account_balance;
    }

    /**
     * Send the email verification notification using our custom queued notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail());
    }

    /**
     * Check if the user is currently suspended
     */
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    /**
     * Get the user's referral URL using their username
     */
    public function getReferralUrl(): string
    {
        // If username exists, use it; otherwise fallback to user ID
        $referralCode = $this->username ?: $this->id;
        return route('register', ['ref' => $referralCode]);
    }

    /**
     * Suspend the user
     */
    public function suspend(): void
    {
        $this->update(['suspended_at' => now()]);
    }

    /**
     * Unsuspend the user
     */
    public function unsuspend(): void
    {
        $this->update(['suspended_at' => null]);
    }

    /**
     * Generate a unique account ID with 5 digits and 3 letters (e.g., 12345ABC)
     */
    public static function generateAccountId(): string
    {
        do {
            // Generate 5 random digits
            $digits = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);

            // Generate 3 random uppercase letters
            $letters = '';
            for ($i = 0; $i < 3; $i++) {
                $letters .= chr(random_int(65, 90)); // A-Z
            }

            $accountId = $digits . $letters;
        } while (static::where('account_id', $accountId)->exists());

        return $accountId;
    }

    /**
     * Boot method to auto-generate account ID when creating a user
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->account_id)) {
                $user->account_id = static::generateAccountId();
            }
        });
    }

    // Attendance System Methods
    /**
     * Check if user has logged in today
     */
    public function hasLoggedInToday(): bool
    {
        return DailyAttendance::hasLoggedInToday($this->id);
    }

    /**
     * Record today's attendance and return the attendance record
     */
    public function recordTodaysAttendance(): DailyAttendance
    {
        return DailyAttendance::recordTodaysAttendance($this->id);
    }

    /**
     * Get user's attendance for current month
     */
    public function getMonthlyAttendance($month = null)
    {
        return DailyAttendance::getMonthlyAttendance($this->id, $month);
    }

    /**
     * Get user's total tickets for current month
     */
    public function getMonthlyTicketCount($month = null): int
    {
        return DailyAttendance::getMonthlyTicketCount($this->id, $month);
    }

    /**
     * Check if user should see attendance modal (first login today)
     */
    public function shouldShowAttendanceModal(): bool
    {
        return !$this->hasLoggedInToday();
    }
}
