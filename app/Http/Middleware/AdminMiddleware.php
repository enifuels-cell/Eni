<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Check if user is admin (you can customize this logic)
        // For now, we'll check if user email contains 'admin' or user ID is 1
        $user = auth()->user();
        if ($user->id === 1 || str_contains($user->email, 'admin')) {
            return $next($request);
        }

        abort(403, 'Access denied. Admin privileges required.');
    }
}
