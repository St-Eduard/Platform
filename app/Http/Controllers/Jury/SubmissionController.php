<?php

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    public function index()
    {
        if (!Auth::check() || !Auth::user()->isJury()) {
            abort(403);
        }

        $submissions = Submission::with(['user', 'contest'])
            ->whereIn('status', ['submitted', 'needs_fix'])
            ->latest()
            ->paginate(15);
        
        return view('jury.submissions.index', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        if (!Auth::check() || !Auth::user()->isJury()) {
            abort(403);
        }

        $submission->load(['user', 'contest', 'comments.user', 'attachments']);
        return view('jury.submissions.show', compact('submission'));
    }

    public function changeStatus(Request $request, Submission $submission)
    {
        if (!Auth::check() || !Auth::user()->isJury()) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:accepted,rejected,needs_fix']);

        try {
            $this->submissionService->changeStatus($submission, $request->status);
            return redirect()->back()->with('success', 'Статус изменен');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addComment(Request $request, Submission $submission)
    {
        if (!Auth::check() || !Auth::user()->isJury()) {
            abort(403);
        }

        $request->validate(['comment' => 'required|string|max:1000']);

        try {
            $this->submissionService->addComment($submission, Auth::id(), $request->comment);
            return back()->with('success', 'Комментарий добавлен');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}