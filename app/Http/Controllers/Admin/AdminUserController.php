<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    /** Show all users */
    public function index()
    {
        try {
            $users = User::latest()->paginate(15);
            return view('admin.users.index', compact('users'));
        } catch (\Throwable $e) {
            Log::error('Error loading user list: ' . $e->getMessage());
            return back()->with('error', 'Error loading user list.');
        }
    }

    /** Show user details with attempts */
    public function show(User $user)
    {
        try {
            $user->load(['attempts.lesson' => function ($query) {
                $query->orderByDesc('created_at');
            }]);

            return view('admin.users.show', compact('user'));
        } catch (\Throwable $e) {
            Log::error('Error loading user data: ' . $e->getMessage());
            return back()->with('error', 'Unable to load user data.');
        }
    }

    /** Delete user */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', 'User deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Error deleting user.');
        }
    }
}
