<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Log an activity to audit_logs table
     */
    public static function log(string $activity, ?Request $request = null, ?int $userId = null): AuditLog
    {
        $resolvedUserId = $userId ?? Auth::id();
        $ipAddress = $request ? $request->ip() : request()->ip();

        return AuditLog::create([
            'user_id'   => $resolvedUserId,
            'aktivitas' => $activity,
            'ip_address' => $ipAddress,
            'waktu'     => now(),
        ]);
    }
}
