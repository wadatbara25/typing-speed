<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * 🚦 Redirect logged-in users away from guest pages.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // 👑 Admin
                if ($user->role === 'admin' && !$request->routeIs('admin.*')) {
                    return redirect()->route('admin.dashboard');
                }

                // 👤 User
                if ($user->role === 'user' && !$request->routeIs('dashboard')) {
                    return redirect()->route('dashboard');
                }

                // ✅ Allow access if already in their dashboard
                return $next($request);
            }
        }

        return $next($request);
    }
}
