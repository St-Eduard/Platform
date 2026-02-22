<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Статистика
        $stats = [
            'users' => [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'jury' => User::where('role', 'jury')->count(),
                'participant' => User::where('role', 'participant')->count(),
                'new_today' => User::whereDate('created_at', today())->count(),
            ],
            'contests' => [
                'total' => Contest::count(),
                'active' => Contest::where('is_active', true)->count(),
                'ended' => Contest::where('deadline_at', '<', now())->count(),
                'new_today' => Contest::whereDate('created_at', today())->count(),
            ],
            'submissions' => [
                'total' => Submission::count(),
                'draft' => Submission::where('status', 'draft')->count(),
                'submitted' => Submission::where('status', 'submitted')->count(),
                'accepted' => Submission::where('status', 'accepted')->count(),
                'rejected' => Submission::where('status', 'rejected')->count(),
                'needs_fix' => Submission::where('status', 'needs_fix')->count(),
            ],
        ];

        // Последние 5 конкурсов
        $recentContests = Contest::latest()->take(5)->get();

        // Последние 5 пользователей
        $recentUsers = User::latest()->take(5)->get();

        // Последние 5 работ
        $recentSubmissions = Submission::with(['user', 'contest'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentContests', 'recentUsers', 'recentSubmissions'));
    }
}