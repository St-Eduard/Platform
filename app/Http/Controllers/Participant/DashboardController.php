<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isParticipant()) {
            abort(403);
        }

        $userId = Auth::id();
        
        // Статистика
        $stats = [
            'total' => Submission::where('user_id', $userId)->count(),
            'draft' => Submission::where('user_id', $userId)->where('status', 'draft')->count(),
            'submitted' => Submission::where('user_id', $userId)->where('status', 'submitted')->count(),
            'accepted' => Submission::where('user_id', $userId)->where('status', 'accepted')->count(),
            'needs_fix' => Submission::where('user_id', $userId)->where('status', 'needs_fix')->count(),
            'rejected' => Submission::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];

        // Последние 5 работ
        $recentSubmissions = Submission::where('user_id', $userId)
            ->with('contest')
            ->latest()
            ->take(5)
            ->get();

        // Последние уведомления
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(5)
            ->get();

        return view('participant.dashboard', compact('stats', 'recentSubmissions', 'notifications'));
    }
}