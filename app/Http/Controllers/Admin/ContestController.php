<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContestRequest;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContestController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $contests = Contest::latest()->paginate(10);
        return view('admin.contests.index', compact('contests'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.contests.create');
    }

    public function store(ContestRequest $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        Contest::create($request->validated());
        return redirect()->route('admin.contests.index')->with('success', 'Конкурс успешно создан');
    }

    public function edit(Contest $contest)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.contests.edit', compact('contest'));
    }

    public function update(ContestRequest $request, Contest $contest)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $contest->update($request->validated());
        return redirect()->route('admin.contests.index')->with('success', 'Конкурс успешно обновлен');
    }

    public function destroy(Contest $contest)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        $contest->delete();
        return redirect()->route('admin.contests.index')->with('success', 'Конкурс успешно удален');
    }
}