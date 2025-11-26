<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use Illuminate\Support\Facades\Log;

class AdminStatisticsController extends Controller
{
    /** Display global statistics dashboard */
    public function index()
    {
        try {
            // General statistics
            $stats = [
                'avg_wpm'        => number_format(Attempt::avg('wpm') ?? 0, 1),
                'avg_accuracy'   => number_format(Attempt::avg('accuracy') ?? 0, 1),
                'total_attempts' => Attempt::count(),
            ];

            // Daily performance stats (last 10 days)
            $daily = Attempt::selectRaw('DATE(created_at) as date, ROUND(AVG(wpm), 1) as avg_wpm')
                ->groupBy('date')
                ->orderByDesc('date')
                ->take(10)
                ->get()
                ->reverse()
                ->values();

            // Return view with data
            return view('admin.statistics.index', compact('stats', 'daily'));
        } catch (\Throwable $e) {
            // Log the error
            Log::error('Error loading statistics: ' . $e->getMessage());

            // Redirect back with error message
            return back()->with('error', 'Error loading statistics page. Please try again later.');
        }
    }
}
