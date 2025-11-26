<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Lesson, Attempt};
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    /** Show admin dashboard with global statistics */
    public function index()
    {
        try {
            // Basic counts
            $usersCount    = User::count();
            $lessonsCount  = Lesson::count();
            $attemptsCount = Attempt::count();

            // Best user by average WPM
            $bestUser = User::with('attempts')->get()
                ->sortByDesc(fn($user) => $user->attempts->avg('wpm') ?? 0)
                ->first();

            // Overall averages
            $avgWpm      = round(Attempt::avg('wpm') ?? 0, 1);
            $avgAccuracy = round(Attempt::avg('accuracy') ?? 0, 1);

            // Performance trend (last 7 days)
            $recentPerformance = Attempt::selectRaw('DATE(created_at) as date, AVG(wpm) as avg_wpm')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->limit(7)
                ->get();

            // Render view
            return view('admin.dashboard', [
                'usersCount'        => $usersCount,
                'lessonsCount'      => $lessonsCount,
                'attemptsCount'     => $attemptsCount,
                'bestUser'          => $bestUser,
                'avgWpm'            => $avgWpm,
                'avgAccuracy'       => $avgAccuracy,
                'recentPerformance' => $recentPerformance,
            ]);

        } catch (\Throwable $e) {
            // Log error details
            Log::error('Error loading admin dashboard', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // Show friendly error
            return back()->with('error', 'Error loading admin dashboard. Please try again later.');
        }
    }
}
