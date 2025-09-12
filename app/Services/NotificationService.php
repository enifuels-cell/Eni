<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;

class NotificationService
{
    public static function createWelcomeNotification(User $user): UserNotification
    {
        return self::create($user, [
            'title' => 'Welcome to ENI!',
            'message' => 'Complete your profile to get started with investments.',
            'category' => 'welcome',
            'type' => 'info',
            'priority' => 'high'
        ]);
    }

    public static function createPinSetupNotification(User $user): UserNotification
    {
        return self::create($user, [
            'title' => 'PIN Status: SET',
            'message' => 'User ID: ' . $user->id,
            'category' => 'security',
            'type' => 'warning',
            'priority' => 'high',
            'action_url' => route('pin.setup.form')
        ]);
    }

    public static function createAccountVerifiedNotification(User $user): UserNotification
    {
        return self::create($user, [
            'title' => 'Account Verified',
            'message' => 'Your account has been successfully verified.',
            'category' => 'account',
            'type' => 'success',
            'priority' => 'medium'
        ]);
    }

    public static function createInvestmentPackageNotification(User $user): UserNotification
    {
        return self::create($user, [
            'title' => 'New Investment Packages',
            'message' => 'Check out our latest high-yield investment options.',
            'category' => 'investment',
            'type' => 'info',
            'priority' => 'medium'
        ]);
    }

    public static function createInvestmentNotification(User $user, string $title, string $message): UserNotification
    {
        return self::create($user, [
            'title' => $title,
            'message' => $message,
            'category' => 'investment',
            'type' => 'success',
            'priority' => 'high'
        ]);
    }

    public static function createReferralNotification(User $user, string $message): UserNotification
    {
        return self::create($user, [
            'title' => 'Referral Bonus Earned',
            'message' => $message,
            'category' => 'referral',
            'type' => 'success',
            'priority' => 'medium'
        ]);
    }

    public static function createTransactionNotification(User $user, string $title, string $message): UserNotification
    {
        return self::create($user, [
            'title' => $title,
            'message' => $message,
            'category' => 'transaction',
            'type' => 'info',
            'priority' => 'medium'
        ]);
    }

    public static function create(User $user, array $data): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'message' => $data['message'],
            'category' => $data['category'],
            'type' => $data['type'] ?? 'info',
            'priority' => $data['priority'] ?? 'medium',
            'action_url' => $data['action_url'] ?? null,
            'expires_at' => $data['expires_at'] ?? null
        ]);
    }

    public static function markAllAsRead(User $user): void
    {
        $user->userNotifications()->unread()->update(['is_read' => true]);
    }

    public static function getUnreadCount(User $user): int
    {
        return $user->userNotifications()->unread()->active()->count();
    }
}
