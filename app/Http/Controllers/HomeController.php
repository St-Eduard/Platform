<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isJury()) {
            return redirect()->route('jury.dashboard');
        } else {
            return redirect()->route('participant.dashboard');
        }
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'total_contests' => Contest::count(),
                'active_contests' => Contest::where('is_active', true)->count(),
                'total_submissions' => Submission::count(),
                'pending_submissions' => Submission::where('status', 'submitted')->count(),
            ];
            return view('admin.dashboard', compact('stats'));
        } 
        elseif ($user->isJury()) {
            $submissions = Submission::with(['user', 'contest'])
                ->whereIn('status', ['submitted', 'needs_fix'])
                ->latest()
                ->paginate(10);
            return view('jury.dashboard', compact('submissions'));
        } 
        else {
            $submissions = Submission::where('user_id', $user->id)
                ->with('contest')
                ->latest()
                ->paginate(10);
            return view('participant.dashboard', compact('submissions'));
        }
    }
}