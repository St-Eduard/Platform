<?php

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Contest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isJury()) {
            abort(403);
        }

        // Статистика по работам
        $stats = [
            'pending' => Submission::where('status', 'submitted')->count(),
            'needs_fix' => Submission::where('status', 'needs_fix')->count(),
            'accepted' => Submission::where('status', 'accepted')->count(),
            'rejected' => Submission::where('status', 'rejected')->count(),
            'total_reviewed' => Submission::whereIn('status', ['accepted', 'rejected', 'needs_fix'])->count(),
        ];

        // Распределение по конкурсам
        $contests = Contest::where('is_active', true)->get();
        $contestStats = [];
        foreach ($contests as $contest) {
            $contestStats[] = [
                'name' => $contest->title,
                'pending' => Submission::where('contest_id', $contest->id)->where('status', 'submitted')->count(),
                'total' => Submission::where('contest_id', $contest->id)->count(),
            ];
        }

        // Работы ожидающие проверки (последние 10)
        $pendingSubmissions = Submission::with(['user', 'contest'])
            ->where('status', 'submitted')
            ->latest()
            ->take(10)
            ->get();

        // Работы требующие доработки (последние 10)
        $needsFixSubmissions = Submission::with(['user', 'contest'])
            ->where('status', 'needs_fix')
            ->latest()
            ->take(10)
            ->get();

        // Моя статистика (сколько проверил)
        $myStats = [
            'accepted' => Submission::where('status', 'accepted')->count(), // В реальности нужно по user_id жюри
            'rejected' => Submission::where('status', 'rejected')->count(),
            'needs_fix' => Submission::where('status', 'needs_fix')->count(),
        ];

        return view('jury.dashboard', compact(
            'stats', 
            'contestStats', 
            'pendingSubmissions', 
            'needsFixSubmissions',
            'myStats'
        ));
    }
}