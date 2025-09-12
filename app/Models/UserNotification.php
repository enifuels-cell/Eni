<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'category',
        'type',
        'priority',
        'action_url',
        'is_read',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active notifications
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope a query to only include unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to filter by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by priority
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Mark the notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
        return $this;
    }

    /**
     * Mark the notification as unread
     */
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
        return $this;
    }

    /**
     * Get the icon for the notification category
     */
    public function getIconAttribute()
    {
        $icons = [
            'security' => 'fas fa-shield-alt',
            'investment' => 'fas fa-chart-line',
            'account' => 'fas fa-user',
            'system' => 'fas fa-cog',
            'welcome' => 'fas fa-hand-wave',
            'referral' => 'fas fa-users',
            'transaction' => 'fas fa-exchange-alt',
            'announcement' => 'fas fa-bullhorn',
            'maintenance' => 'fas fa-tools'
        ];

        return $icons[$this->category] ?? 'fas fa-bell';
    }

    /**
     * Get the category color for styling
     */
    public function getCategoryColorAttribute()
    {
        $colors = [
            'security' => 'red',
            'investment' => 'green',
            'account' => 'blue',
            'system' => 'gray',
            'welcome' => 'purple',
            'referral' => 'pink',
            'transaction' => 'yellow',
            'announcement' => 'indigo',
            'maintenance' => 'orange'
        ];

        return $colors[$this->category] ?? 'blue';
    }
}
