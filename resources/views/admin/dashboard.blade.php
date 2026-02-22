@extends('layouts.app')

@section('title', 'Панель управления')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-light mb-0">Панель управления</h1>
    </div>
</div>

<!-- Статистика пользователей -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-people text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Всего пользователей</div>
                    <div class="stat-value h3 fw-light">{{ $stats['users']['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-shield-lock text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Администраторы</div>
                    <div class="stat-value h3 fw-light">{{ $stats['users']['admin'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-gavel text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Члены жюри</div>
                    <div class="stat-value h3 fw-light">{{ $stats['users']['jury'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-person-workspace text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Участники</div>
                    <div class="stat-value h3 fw-light">{{ $stats['users']['participant'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Статистика конкурсов и работ -->
<div class="row g-4 mb-5">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Конкурсы</h6>
                <span class="badge bg-white text-secondary border">всего {{ $stats['contests']['total'] }}</span>
            </div>
            <div class="card-body px-4">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary">Активные конкурсы</span>
                        <span class="fw-medium">{{ $stats['contests']['active'] }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $activePercent = $stats['contests']['total'] > 0 ? ($stats['contests']['active'] / $stats['contests']['total']) * 100 : 0; @endphp
                        <div class="progress-bar bg-success" style="width: {{ $activePercent }}%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary">Завершенные конкурсы</span>
                        <span class="fw-medium">{{ $stats['contests']['ended'] }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $endedPercent = $stats['contests']['total'] > 0 ? ($stats['contests']['ended'] / $stats['contests']['total']) * 100 : 0; @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $endedPercent }}%"></div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-secondary small">Новых за сегодня</span>
                    <span class="fw-medium">{{ $stats['contests']['new_today'] }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Работы участников</h6>
                <span class="badge bg-white text-secondary border">всего {{ $stats['submissions']['total'] }}</span>
            </div>
            <div class="card-body px-4">
                <div class="row g-4 mb-4">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">На проверке</div>
                            <div class="h4 fw-light">{{ $stats['submissions']['submitted'] }}</div>
                            <div class="small text-warning">
                                {{ $stats['submissions']['total'] > 0 ? round(($stats['submissions']['submitted'] / $stats['submissions']['total']) * 100) : 0 }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">Принято</div>
                            <div class="h4 fw-light">{{ $stats['submissions']['accepted'] }}</div>
                            <div class="small text-success">
                                {{ $stats['submissions']['total'] > 0 ? round(($stats['submissions']['accepted'] / $stats['submissions']['total']) * 100) : 0 }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">Отклонено</div>
                            <div class="h4 fw-light">{{ $stats['submissions']['rejected'] }}</div>
                            <div class="small text-danger">
                                {{ $stats['submissions']['total'] > 0 ? round(($stats['submissions']['rejected'] / $stats['submissions']['total']) * 100) : 0 }}%
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="progress-stacked d-flex" style="height: 8px; border-radius: 4px; overflow: hidden;">
                    @php
                        $submittedPercent = $stats['submissions']['total'] > 0 ? ($stats['submissions']['submitted'] / $stats['submissions']['total']) * 100 : 0;
                        $acceptedPercent = $stats['submissions']['total'] > 0 ? ($stats['submissions']['accepted'] / $stats['submissions']['total']) * 100 : 0;
                        $rejectedPercent = $stats['submissions']['total'] > 0 ? ($stats['submissions']['rejected'] / $stats['submissions']['total']) * 100 : 0;
                        $needsFixPercent = $stats['submissions']['total'] > 0 ? ($stats['submissions']['needs_fix'] / $stats['submissions']['total']) * 100 : 0;
                        $draftPercent = $stats['submissions']['total'] > 0 ? ($stats['submissions']['draft'] / $stats['submissions']['total']) * 100 : 0;
                    @endphp
                    <div class="progress-bar bg-warning" style="width: {{ $submittedPercent }}%" title="На проверке: {{ $stats['submissions']['submitted'] }}"></div>
                    <div class="progress-bar bg-success" style="width: {{ $acceptedPercent }}%" title="Принято: {{ $stats['submissions']['accepted'] }}"></div>
                    <div class="progress-bar bg-danger" style="width: {{ $rejectedPercent }}%" title="Отклонено: {{ $stats['submissions']['rejected'] }}"></div>
                    <div class="progress-bar" style="background: #ffc107; width: {{ $needsFixPercent }}%" title="На доработке: {{ $stats['submissions']['needs_fix'] }}"></div>
                    <div class="progress-bar bg-secondary" style="width: {{ $draftPercent }}%" title="Черновики: {{ $stats['submissions']['draft'] }}"></div>
                </div>
                
                <div class="d-flex flex-wrap gap-3 mt-4 small">
                    <div class="d-flex align-items-center">
                        <span class="legend-color bg-warning me-2" style="width: 12px; height: 12px; border-radius: 3px;"></span>
                        <span class="text-secondary">На проверке ({{ $stats['submissions']['submitted'] }})</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="legend-color bg-success me-2" style="width: 12px; height: 12px; border-radius: 3px;"></span>
                        <span class="text-secondary">Принято ({{ $stats['submissions']['accepted'] }})</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="legend-color bg-danger me-2" style="width: 12px; height: 12px; border-radius: 3px;"></span>
                        <span class="text-secondary">Отклонено ({{ $stats['submissions']['rejected'] }})</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="legend-color me-2" style="width: 12px; height: 12px; border-radius: 3px; background: #ffc107;"></span>
                        <span class="text-secondary">На доработке ({{ $stats['submissions']['needs_fix'] }})</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="legend-color bg-secondary me-2" style="width: 12px; height: 12px; border-radius: 3px;"></span>
                        <span class="text-secondary">Черновики ({{ $stats['submissions']['draft'] }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Последние записи -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Последние конкурсы</h6>
                <a href="{{ route('admin.contests.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3">Все</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentContests as $contest)
                    <div class="list-item px-4 py-3 border-bottom">
                        <div class="list-item-content">
                            <div class="fw-medium mb-1">
                                <a href="{{ route('admin.contests.edit', $contest) }}" class="text-decoration-none text-dark">
                                    {{ $contest->title }}
                                </a>
                            </div>
                            <div class="small text-secondary d-flex align-items-center gap-3">
                                <span><i class="bi bi-calendar me-1"></i> до {{ $contest->deadline_at->format('d.m.Y') }}</span>
                                @if($contest->is_active)
                                    <span class="badge bg-white text-success border border-success">Активен</span>
                                @else
                                    <span class="badge bg-white text-secondary border">Завершен</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-3 text-secondary text-center">Нет конкурсов</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Новые пользователи</h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3">Все</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentUsers as $user)
                    <div class="list-item px-4 py-3 border-bottom">
                        <div class="list-item-content">
                            <div class="fw-medium mb-1">{{ $user->name }}</div>
                            <div class="small text-secondary d-flex align-items-center gap-3">
                                <span><i class="bi bi-envelope me-1"></i> {{ $user->email }}</span>
                                <span class="badge role-{{ $user->role }}">
                                    {{ $user->isAdmin() ? 'Админ' : ($user->isJury() ? 'Жюри' : 'Участник') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-3 text-secondary text-center">Нет пользователей</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Последние работы</h6>
                <span class="badge bg-white text-secondary border">всего {{ $stats['submissions']['total'] }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($recentSubmissions as $submission)
                    <div class="list-item px-4 py-3 border-bottom">
                        <div class="list-item-content">
                            <div class="fw-medium mb-1">{{ $submission->title }}</div>
                            <div class="small text-secondary mb-2">
                                <span class="me-3"><i class="bi bi-person me-1"></i> {{ $submission->user->name }}</span>
                                <span><i class="bi bi-trophy me-1"></i> {{ $submission->contest->title }}</span>
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
                    </div>
                @empty
                    <div class="px-4 py-3 text-secondary text-center">Нет работ</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Быстрые действия</h6>
            </div>
            <div class="card-body px-4">
                <a href="{{ route('admin.contests.create') }}" class="btn btn-dark rounded-pill px-4 me-2">
                    <i class="bi bi-plus-circle me-1"></i> Новый конкурс
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-secondary rounded-pill px-4">
                    <i class="bi bi-person-plus me-1"></i> Новый пользователь
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1.25rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 0.375rem;
}

.stat-icon i {
    font-size: 1.5rem;
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

.progress {
    background: #e9ecef;
    border-radius: 999px;
}

.border-bottom {
    border-bottom: 1px solid #e9ecef !important;
}

.border-top {
    border-top: 1px solid #e9ecef !important;
}
</style>
@endsection