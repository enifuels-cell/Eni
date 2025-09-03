<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $company_name
 * @property string $contact_person
 * @property string $email
 * @property string $phone
 * @property string|null $address
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication rejected()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FranchiseApplication whereUserId($value)
 * @mixin \Eloquent
 */
class FranchiseApplication extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'status',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
