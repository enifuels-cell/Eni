<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ExtendAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Extend session lifetime to 24 hours for admins (1440 minutes)
            config(['session.lifetime' => 1440]);
            
            // Log admin activity
            $this->logAdminActivity($request);
        }

        return $next($request);
    }

    private function logAdminActivity(Request $request)
    {
        $admin = Auth::user();
        
        // Log admin session activity
        \Log::info('Admin Activity', [
            'admin_id' => $admin->id,
            'admin_email' => $admin->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()
        ]);
    }
}
