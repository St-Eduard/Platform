@extends('layouts.app')

@section('title', 'Работы на проверку')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 px-4 py-3">
        <h5 class="fw-light mb-0">Работы на проверку</h5>
    </div>
    <div class="card-body p-0">
        <!-- Фильтры -->
        <div class="px-4 py-3 border-bottom">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Поиск по названию" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">Все статусы</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>На проверке</option>
                        <option value="needs_fix" {{ request('status') == 'needs_fix' ? 'selected' : '' }}>Требуют доработки</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3 me-2">Применить</button>
                    <a href="{{ route('jury.submissions.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3">Сброс</a>
                </div>
            </form>
        </div>

        <!-- Список работ -->
        @if($submissions->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-check2-square" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 text-secondary">Нет работ для проверки</p>
            </div>
        @else
            <div class="jury-submission-list">
                @foreach($submissions as $submission)
                    <a href="{{ route('jury.submissions.show', $submission) }}" class="jury-submission-item">
                        <div class="jury-submission-content">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h6 class="submission-title">{{ $submission->title }}</h6>
                                    <div class="submission-meta">
                                        <span><i class="bi bi-person me-1"></i> {{ $submission->user->name }}</span>
                                        <span><i class="bi bi-trophy me-1"></i> {{ $submission->contest->title }}</span>
                                        <span><i class="bi bi-files me-1"></i> {{ $submission->attachments->count() }} файлов</span>
                                        <span><i class="bi bi-calendar me-1"></i> {{ $submission->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                                <span class="badge status-{{ $submission->status }}">
                                    {{ $submission->status == 'submitted' ? 'На проверке' : 'Доработка' }}
                                </span>
                            </div>
                            
                            @if($submission->comments->count() > 0)
                                <div class="mt-2 small text-secondary">
                                    <i class="bi bi-chat me-1"></i> Есть комментарии
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="px-4 py-3 border-top">
                {{ $submissions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.jury-submission-list {
    display: flex;
    flex-direction: column;
}

.jury-submission-item {
    display: block;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    text-decoration: none;
    color: inherit;
    transition: background-color 0.15s;
}

.jury-submission-item:hover {
    background-color: #fafafa;
}

.jury-submission-item:last-child {
    border-bottom: none;
}

.submission-title {
    font-weight: 500;
    color: #212529;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.submission-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.submission-meta i {
    color: #adb5bd;
}

.badge {
    font-weight: 400;
    padding: 0.35rem 0.75rem;
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #e9ecef;
}

.status-submitted {
    background: #f8f9fa;
    color: #0d6efd;
    border-color: #cfe2ff;
}

.status-needs_fix {
    background: #f8f9fa;
    color: #ffc107;
    border-color: #fff3cd;
}
</style>
@endsection 