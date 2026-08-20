<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:teacher')
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Parent view uses session key 'parent_view_id' and is handled separately
        if ($role === 'parent') {
            if (session()->has('parent_view_id')) {
                return $next($request);
            }
            abort(403, 'Parent access denied');
        }

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login.student');
        }

        if ($user->role !== $role) {
            // Log for debugging
            Log::warning("Access denied: User role '{$user->role}' does not match required role '{$role}'");
            abort(403, "Access denied. Your role is '{$user->role}', but '{$role}' is required.");
        }

        return $next($request);
    }
}
