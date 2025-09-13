<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

class AuditLogger
{
    public static function log(?Authenticatable $user, string $action, $subject = null, array $meta = []): void
    {
        $subjectType = null; $subjectId = null;
        if ($subject) {
            $subjectType = is_object($subject) ? get_class($subject) : (string)$subject;
            if (is_object($subject) && method_exists($subject, 'getKey')) {
                $subjectId = $subject->getKey();
            }
        }

        ActivityLog::create([
            'user_id' => $user?->getAuthIdentifier(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'ip' => request()->ip(),
            'user_agent' => substr((string) request()->header('User-Agent'), 0, 255),
            'meta' => $meta
        ]);

        Log::channel('investment')->info('audit', [
            'action' => $action,
            'user_id' => $user?->getAuthIdentifier(),
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'meta' => $meta
        ]);
    }
}
