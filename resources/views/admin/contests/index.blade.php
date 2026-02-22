@extends('layouts.app')

@section('title', 'Конкурсы')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-light mb-0">Конкурсы</h5>
        <a href="{{ route('admin.contests.create') }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i class="bi bi-plus me-1"></i> Новый конкурс
        </a>
    </div>
    <div class="card-body p-0">
        <div class="admin-contest-list">
            @foreach($contests as $contest)
                <a href="{{ route('admin.contests.edit', $contest) }}" class="admin-contest-item">
                    <div class="admin-contest-content">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h6 class="contest-name">{{ $contest->title }}</h6>
                                <div class="contest-meta">
                                    <span><i class="bi bi-calendar me-1"></i> Дедлайн: {{ $contest->deadline_at->format('d.m.Y H:i') }}</span>
                                    <span><i class="bi bi-people me-1"></i> Работ: {{ $contest->submissions->count() }}</span>
                                    <span><i class="bi bi-clock me-1"></i> Создан: {{ $contest->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                            @if($contest->is_active)
                                <span class="badge bg-white text-success border border-success">Активен</span>
                            @else
                                <span class="badge bg-white text-secondary border">Неактивен</span>
                            @endif
                        </div>
                        <p class="contest-description mt-2">{{ Str::limit($contest->description, 150) }}</p>
                    </div>
                    <div class="contest-action">
                        <span class="btn btn-sm btn-secondary rounded-pill px-3">Редактировать</span>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="px-4 py-3 border-top">
            {{ $contests->links() }}
        </div>
    </div>
</div>

<style>
.admin-contest-list {
    display: flex;
    flex-direction: column;
}

.admin-contest-item {
    display: flex;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    text-decoration: none;
    color: inherit;
    transition: background-color 0.15s;
}

.admin-contest-item:hover {
    background-color: #fafafa;
}

.admin-contest-item:last-child {
    border-bottom: none;
}

.admin-contest-content {
    flex: 1;
}

.contest-name {
    font-weight: 500;
    color: #212529;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.contest-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.contest-meta i {
    color: #adb5bd;
}

.contest-description {
    color: #495057;
    font-size: 0.9375rem;
    margin: 0.5rem 0 0 0;
}

.contest-action {
    margin-left: 1.5rem;
}

.contest-action .btn {
    pointer-events: none;
}

.badge {
    font-weight: 400;
    padding: 0.35rem 0.75rem;
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #e9ecef;
    pointer-events: none;
}

.badge.bg-white {
    background: #ffffff !important;
}

.border-success {
    border-color: #198754 !important;
}

.text-success {
    color: #198754 !important;
}
</style>
@endsection