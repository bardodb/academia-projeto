<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has the required role
        if ($user->role !== $role) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this area.');
        }

        // Check for required profiles
        if ($role === 'student' && !$user->student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found. Please contact support.');
        }

        if ($role === 'instructor' && !$user->instructor) {
            return redirect()->route('dashboard')->with('error', 'Instructor profile not found. Please contact support.');
        }

        return $next($request);
    }
} 