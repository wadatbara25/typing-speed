<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /** Show the user profile page */
    public function edit()
    {
        $user = Auth::user();

        // Typing statistics summary
        $stats = [
            'attempts_count' => $user->attempts()->count() ?? 0,
            'best_wpm'       => round($user->attempts()->max('wpm') ?? 0, 1),
            'best_accuracy'  => round($user->attempts()->max('accuracy') ?? 0, 1),
            'avg_wpm'        => round($user->attempts()->avg('wpm') ?? 0, 1),
            'avg_accuracy'   => round($user->attempts()->avg('accuracy') ?? 0, 1),
        ];

        return view('profile.edit', compact('user', 'stats'));
    }

    /** Update profile information */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:150',
        ]);

        // Save changes
        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /** Delete user account */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Logout then delete
        Auth::logout();
        $user->delete();

        return redirect('/')
            ->with('success', 'Account deleted successfully.');
    }
}
