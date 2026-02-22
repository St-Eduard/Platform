@extends('layouts.app')

@section('title', 'Мои работы')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-light mb-0">Мои работы</h5>
        <a href="{{ route('participant.submissions.create') }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i class="bi bi-plus me-1"></i> Новая работа
        </a>
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
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Черновики</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>На проверке</option>
                        <option value="needs_fix" {{ request('status') == 'needs_fix' ? 'selected' : '' }}>Доработка</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Принятые</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Отклоненные</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3 me-2">Применить</button>
                    <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3">Сброс</a>
                </div>
            </form>
        </div>

        <!-- Список работ -->
        @if($submissions->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 text-secondary">У вас пока нет работ</p>
                <a href="{{ route('participant.submissions.create') }}" class="btn btn-dark rounded-pill px-4">
                    Создать первую работу
                </a>
            </div>
        @else
            <div class="submission-list">
                @foreach($submissions as $submission)
                    <a href="{{ route('participant.submissions.show', $submission) }}" class="submission-item">
                        <div class="submission-item-content">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h6 class="submission-title">{{ $submission->title }}</h6>
                                    <div class="submission-meta">
                                        <span><i class="bi bi-trophy me-1"></i> {{ $submission->contest->title }}</span>
                                        <span><i class="bi bi-files me-1"></i> {{ $submission->attachments->count() }}/3 файлов</span>
                                        <span><i class="bi bi-chat me-1"></i> {{ $submission->comments->count() }} комм.</span>
                                        <span><i class="bi bi-calendar me-1"></i> {{ $submission->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>
                                <span class="badge status-{{ $submission->status }}">
                                    @switch($submission->status)
                                        @case('draft') Черновик @break
                                        @case('submitted') На проверке @break
                                        @case('needs_fix') Доработка @break
                                        @case('accepted') Принята @break
                                        @case('rejected') Отклонена @break
                                    @endswitch
                                </span>
                            </div>
                            
                            @if($submission->status == 'needs_fix')
                                <div class="mt-2 small text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Требуется доработка
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
.submission-list {
    display: flex;
    flex-direction: column;
}

.submission-item {
    display: block;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    text-decoration: none;
    color: inherit;
    transition: background-color 0.15s;
}

.submission-item:hover {
    background-color: #fafafa;
}

.submission-item:last-child {
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

.status-draft {
    background: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
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

.status-accepted {
    background: #f8f9fa;
    color: #198754;
    border-color: #d1e7dd;
}

.status-rejected {
    background: #f8f9fa;
    color: #dc3545;
    border-color: #f8d7da;
}
</style>
@endsection