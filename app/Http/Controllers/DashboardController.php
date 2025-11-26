<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\{Lesson, Attempt, User};

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            $lessonsCount  = Lesson::count();
            $attemptsCount = $user->attempts()->count();

            $bestSpeed    = round($user->attempts()->max('wpm') ?? 0, 1);
            $bestAccuracy = round($user->attempts()->max('accuracy') ?? 0, 1);

            $recentAttempts = $user->attempts()
                ->with('lesson:id,title')
                ->latest()
                ->take(10)
                ->get();

            $topUsers = User::select('users.id', 'users.name')
                ->join('attempts', 'attempts.user_id', '=', 'users.id')
                ->selectRaw('MAX(attempts.wpm) as best_wpm')
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('best_wpm')
                ->limit(5)
                ->get();

            if (blank($user->last_login_at) || $user->last_login_at->lt(now()->startOfDay())) {
                $user->forceFill(['last_login_at' => now()])->saveQuietly();
            }

            return view('dashboard.index', compact(
                'lessonsCount',
                'attemptsCount',
                'bestSpeed',
                'bestAccuracy',
                'recentAttempts',
                'topUsers'
            ));

        } catch (\Throwable $e) {
            Log::error('❌ خطأ أثناء تحميل لوحة التحكم', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'حدث خطأ أثناء تحميل لوحة التحكم. حاول لاحقًا.');
        }
    }
}
