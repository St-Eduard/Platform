<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function index()
    {
        $contests = Contest::where('is_active', true)
            ->orderBy('deadline_at')
            ->paginate(10);
        
        return view('contests.index', compact('contests'));
    }

    public function show(Contest $contest)
    {
        return view('contests.show', compact('contest'));
    }
}