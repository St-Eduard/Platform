<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmissionRequest;
use App\Models\Contest;
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
        if (!Auth::check() || !Auth::user()->isParticipant()) {
            abort(403);
        }

        $submissions = Submission::where('user_id', Auth::id())
            ->with('contest')
            ->latest()
            ->paginate(10);
        
        return view('participant.submissions.index', compact('submissions'));
    }

    public function create(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isParticipant()) {
            abort(403);
        }

        $contests = Contest::where('is_active', true)
            ->where('deadline_at', '>', now())
            ->get();
        
        // Если передан конкретный конкурс
        $selectedContest = null;
        if ($request->has('contest_id')) {
            $selectedContest = Contest::find($request->contest_id);
        }
        
        return view('participant.submissions.create', compact('contests', 'selectedContest'));
    }

    public function store(SubmissionRequest $request)
    {
        if (!Auth::check() || !Auth::user()->isParticipant()) {
            abort(403);
        }

        try {
            $submission = $this->submissionService->create($request->validated(), Auth::id());
            return redirect()->route('participant.submissions.show', $submission)
                ->with('success', 'Черновик создан');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Submission $submission)
    {
        if (!Auth::check() || $submission->user_id !== Auth::id()) {
            abort(403);
        }
        
        $submission->load(['comments.user', 'attachments', 'contest']);
        return view('participant.submissions.show', compact('submission'));
    }

    public function edit(Submission $submission)
    {
        if (!Auth::check() || $submission->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$submission->canBeEdited()) {
            return redirect()->route('participant.submissions.show', $submission)
                ->with('error', 'Нельзя редактировать');
        }

        $contests = Contest::where('is_active', true)->get();
        return view('participant.submissions.edit', compact('submission', 'contests'));
    }

    public function update(SubmissionRequest $request, Submission $submission)
    {
        if (!Auth::check() || $submission->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->submissionService->update($submission, $request->validated());
            return redirect()->route('participant.submissions.show', $submission)
                ->with('success', 'Работа обновлена');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function submit(Submission $submission)
    {
        if (!Auth::check() || $submission->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $this->submissionService->submit($submission);
            return redirect()->route('participant.submissions.show', $submission)
                ->with('success', 'Работа отправлена на проверку');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addComment(Request $request, Submission $submission)
    {
        if (!Auth::check() || $submission->user_id !== Auth::id()) {
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