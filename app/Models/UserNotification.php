<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'user_notifications';
    
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'category',
        'action_url',
        'is_read',
        'priority',
        'expires_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getIconAttribute()
    {
        return match($this->category) {
            'security' => 'fas fa-shield-alt',
            'investment' => 'fas fa-chart-line',
            'account' => 'fas fa-user',
            'system' => 'fas fa-cog',
            'welcome' => 'fas fa-hand-wave',
            'referral' => 'fas fa-users',
            'transaction' => 'fas fa-exchange-alt',
            default => 'fas fa-bell'
        };
    }

    public function getCategoryColorAttribute()
    {
        return match($this->category) {
            'security' => 'purple',
            'investment' => 'green',
            'account' => 'blue',
            'system' => 'gray',
            'welcome' => 'yellow',
            'referral' => 'pink',
            'transaction' => 'indigo',
            default => 'gray'
        };
    }
}
