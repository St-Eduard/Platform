@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-light mb-0">Пользователи</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i class="bi bi-plus me-1"></i> Новый пользователь
        </a>
    </div>
    <div class="card-body p-0">
        <div class="users-list">
            @foreach($users as $user)
                <a href="{{ route('admin.users.edit', $user) }}" class="user-item">
                    <div class="user-avatar">
                        <i class="bi bi-person-circle text-secondary"></i>
                    </div>
                    <div class="user-content">
                        <div class="d-flex align-items-center gap-3">
                            <h6 class="user-name">{{ $user->name }}</h6>
                            <span class="badge role-{{ $user->role }}">
                                {{ $user->isAdmin() ? 'Администратор' : ($user->isJury() ? 'Жюри' : 'Участник') }}
                            </span>
                        </div>
                        <div class="user-meta">
                            <span><i class="bi bi-envelope me-1"></i> {{ $user->email }}</span>
                            <span><i class="bi bi-calendar me-1"></i> {{ $user->created_at->format('d.m.Y') }}</span>
                            <span><i class="bi bi-files me-1"></i> Работ: {{ $user->submissions->count() }}</span>
                        </div>
                    </div>
                    <div class="user-action">
                        <span class="btn btn-sm btn-secondary rounded-pill px-3">Редактировать</span>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="px-4 py-3 border-top">
            {{ $users->links() }}
        </div>
    </div>
</div>

<style>
.users-list {
    display: flex;
    flex-direction: column;
}

.user-item {
    display: flex;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    text-decoration: none;
    color: inherit;
    transition: background-color 0.15s;
    gap: 1rem;
}

.user-item:hover {
    background-color: #fafafa;
}

.user-item:last-child {
    border-bottom: none;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.user-content {
    flex: 1;
}

.user-name {
    font-weight: 500;
    color: #212529;
    margin: 0;
    font-size: 1rem;
}

.user-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.375rem;
}

.user-meta i {
    color: #adb5bd;
}

.user-action .btn {
    pointer-events: none;
}

.badge {
    font-weight: 400;
    padding: 0.35rem 0.75rem;
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #e9ecef;
}

.role-admin {
    background: #f8f9fa;
    color: #dc3545;
    border-color: #f8d7da;
}

.role-jury {
    background: #f8f9fa;
    color: #ffc107;
    border-color: #fff3cd;
}

.role-participant {
    background: #f8f9fa;
    color: #198754;
    border-color: #d1e7dd;
}

.border-top {
    border-top: 1px solid #e9ecef !important;
}
</style>
@endsection