<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectByRole
{
    /**
     * Handle an incoming request.
     * Redirects users to appropriate dashboard if they try to access the wrong one
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // If teacher tries to access /dashboard, redirect to teacher dashboard
        if ($user && $user->role === 'teacher' && $request->path() === 'dashboard') {
            return redirect()->route('teacher.dashboard');
        }
        
        // If student tries to access /teacher/dashboard, let middleware handle it (will show 403)
        // Or we could redirect them to /dashboard if we want:
        // if ($user && $user->role === 'student' && str_starts_with($request->path(), 'teacher/')) {
        //     return redirect()->route('dashboard');
        // }
        
        return $next($request);
    }
}
