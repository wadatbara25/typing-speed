<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * 🧩 Show the login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * 🔐 Handle the login request.
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        // 🔒 Attempt authentication
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('البريد الإلكتروني أو كلمة المرور غير صحيحة.'),
            ]);
        }

        // 🔄 Regenerate session for security
        $request->session()->regenerate();

        // ✅ Smart redirect using RouteServiceProvider::HOME
        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('success', '🎉 تم تسجيل الدخول بنجاح!');
    }

    /**
     * 🚪 Log the user out and destroy session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('info', '👋 تم تسجيل الخروج بنجاح.');
    }
}
