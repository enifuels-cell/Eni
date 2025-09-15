<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
class User extends Authenticatable implements MustVerifyEmail
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
        'account_balance',
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

    // Helper methods for investment platform
    public function totalInvestedAmount(): float
    {
        return $this->investments()->active()->sum('amount');
    }

    public function totalInterestEarned(): float
    {
        return $this->investments()->active()->sum('total_interest_earned');
    }

    public function totalReferralBonuses(): float
    {
        return \App\Models\ReferralBonus::whereHas('referral', function($query) {
            $query->where('referrer_id', $this->id);
        })->where('paid', true)->sum('bonus_amount');
    }

    public function accountBalance(): float
    {
        $credits = $this->transactions()
            ->whereIn('type', ['deposit', 'interest', 'referral_bonus'])
            ->whereIn('status', ['completed', 'approved']) // Include approved deposits
            ->sum('amount');

        $transfers = $this->transactions()
            ->where('type', 'transfer')
            ->where('status', 'completed')
            ->sum('amount');

        $withdrawals = $this->transactions()
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        $other = $this->transactions()
            ->where('type', 'other')
            ->where('status', 'completed')
            ->sum('amount');

        return $credits + $transfers + $other - $withdrawals;
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
}
