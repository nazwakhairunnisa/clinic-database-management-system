<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user's role is in allowed roles
        if (!in_array($user->role, $roles)) {
            // Redirect ke dashboard yang sesuai dengan role mereka
            return match($user->role) {
                'super admin', 'dokter' => redirect()->route('owner.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                'user' => redirect()->route('user.dashboard'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}
