@extends('layouts.app')

@section('title', 'Конкурсы')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h5 class="fw-light mb-0">Активные конкурсы</h5>
            </div>
            <div class="card-body p-0">
                @if($contests->isEmpty())
                    <div class="text-muted text-center py-5">
                        <i class="bi bi-trophy" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="mt-3">Нет активных конкурсов</p>
                    </div>
                @else
                    <div class="contest-list">
                        @foreach($contests as $contest)
                            <a href="{{ route('contests.show', $contest) }}" class="contest-item">
                                <div class="contest-item-content">
                                    <h6 class="contest-title">{{ $contest->title }}</h6>
                                    <div class="contest-meta">
                                        <span><i class="bi bi-calendar me-1"></i> Дедлайн: {{ $contest->deadline_at->format('d.m.Y H:i') }}</span>
                                        <span><i class="bi bi-people me-1"></i> Участников: {{ $contest->submissions->count() }}</span>
                                    </div>
                                    <p class="contest-description">{{ Str::limit($contest->description, 200) }}</p>
                                </div>
                                <div class="contest-action">
                                    @auth
                                        @if(auth()->user()->isParticipant())
                                            <span class="btn btn-sm btn-dark rounded-pill px-3">Участвовать</span>
                                        @endif
                                    @else
                                        <span class="btn btn-sm btn-outline-secondary rounded-pill px-3">Войти для участия</span>
                                    @endauth
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    <div class="px-4 py-3 border-top">
                        {{ $contests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.contest-list {
    display: flex;
    flex-direction: column;
}

.contest-item {
    display: flex;
    align-items: center;
    padding: 1.25rem 1.5rem;
    text-decoration: none;
    color: inherit;
    transition: background-color 0.2s;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.contest-item:last-child {
    border-bottom: none;
}

.contest-item:hover {
    background-color: #fafafa;
}

.contest-item-content {
    flex: 1;
}

.contest-title {
    font-weight: 500;
    color: #212529;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.contest-meta {
    display: flex;
    gap: 1.5rem;
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.contest-meta i {
    color: #adb5bd;
}

.contest-description {
    color: #495057;
    font-size: 0.9375rem;
    margin: 0;
    line-height: 1.5;
}

.contest-action {
    margin-left: 1.5rem;
}

.contest-action .btn {
    pointer-events: none;
    font-weight: 400;
}

.contest-action .btn-dark {
    background-color: #212529;
    border-color: #212529;
}

.contest-action .btn-outline-secondary {
    border-color: #dee2e6;
    color: #6c757d;
}
</style>
@endsection