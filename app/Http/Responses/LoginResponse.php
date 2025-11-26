<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * 🎯 توجيه المستخدم بعد تسجيل الدخول حسب الدور
     */
    public function toResponse($request)
    {
        $user = $request->user();

        // 👑 مدير النظام
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 👤 المستخدم العادي
        if ($user->role === 'user') {
            return redirect()->route('dashboard');
        }

        // 🚦 دور غير معروف
        return redirect()->route('home');
    }
}
