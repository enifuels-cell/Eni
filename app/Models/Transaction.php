<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property numeric $amount
 * @property string|null $reference
 * @property string $status
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction byType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'reference',
        'status',
        'description',
        'receipt_path',
        'processed_at',
        'receipt_code',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (!$model->receipt_code) {
                $model->receipt_code = static::uniqueReceiptCode();
            }
        });
    }

    protected static function uniqueReceiptCode(int $length = 8): string
    {
        $tries = 0; $code = '';
        do {
            $tries++;
            $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
            $code = collect(range(1, $length))->map(fn() => $alphabet[random_int(0, strlen($alphabet)-1)])->implode('');
        } while (self::where('receipt_code', $code)->exists() && $tries < 10);
        return $code;
    }
}
