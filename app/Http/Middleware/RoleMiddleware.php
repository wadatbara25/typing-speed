<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // السماح بالأدوار المطابقة
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // منع الحلقة — لا تعيد توجيه المستخدم إلى نفس صفحته
        if ($user->role === 'admin' && !$request->routeIs('admin.*')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'user' && !$request->routeIs('dashboard')) {
            return redirect()->route('dashboard');
        }

        abort(403, 'غير مصرح لك بالدخول إلى هذه الصفحة.');
    }
}
