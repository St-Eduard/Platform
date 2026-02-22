@extends('layouts.app')

@section('title', 'Панель жюри')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-light mb-0">Панель жюри</h1>
    </div>
</div>

<!-- Статистика -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-clock-history text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Ожидают проверки</div>
                    <div class="stat-value h3 fw-light">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-arrow-repeat text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">На доработке</div>
                    <div class="stat-value h3 fw-light">{{ $stats['needs_fix'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-check-circle text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Принято работ</div>
                    <div class="stat-value h3 fw-light">{{ $stats['accepted'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-x-circle text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Отклонено работ</div>
                    <div class="stat-value h3 fw-light">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Прогресс по конкурсам -->
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Прогресс проверки по конкурсам</h6>
                <span class="badge bg-white text-secondary border">{{ count($contestStats) }} активных</span>
            </div>
            <div class="card-body px-4">
                @forelse($contestStats as $stat)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="small">
                                <span class="fw-medium">{{ $stat['name'] }}</span>
                                <span class="text-secondary ms-2">всего: {{ $stat['total'] }}</span>
                            </div>
                            <span class="badge bg-white text-warning border border-warning">{{ $stat['pending'] }} на проверке</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php $progress = $stat['total'] > 0 ? (($stat['total'] - $stat['pending']) / $stat['total']) * 100 : 0; @endphp
                            <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            @if($stat['pending'] > 0)
                                <div class="progress-bar bg-warning" style="width: {{ ($stat['pending'] / $stat['total']) * 100 }}%"></div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mt-2 small">
                            <span class="text-success">✓ Проверено: {{ $stat['total'] - $stat['pending'] }}</span>
                            <span class="text-warning">⏳ Осталось: {{ $stat['pending'] }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary text-center small py-4">Нет активных конкурсов</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Списки работ -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Ожидают проверки</h6>
                <a href="{{ route('jury.submissions.index', ['status' => 'submitted']) }}" class="btn btn-sm btn-dark rounded-pill px-3">
                    Все {{ $stats['pending'] }}
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($pendingSubmissions as $submission)
                    <div class="list-item px-4 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">
                                    <a href="{{ route('jury.submissions.show', $submission) }}" class="text-decoration-none text-dark">
                                        {{ $submission->title }}
                                    </a>
                                </div>
                                <div class="small text-secondary mb-2">
                                    <span class="me-3"><i class="bi bi-person me-1"></i> {{ $submission->user->name }}</span>
                                    <span class="me-3"><i class="bi bi-trophy me-1"></i> {{ $submission->contest->title }}</span>
                                    <span><i class="bi bi-clock me-1"></i> {{ $submission->created_at->diffForHumans() }}</span>
                                </div>
                                <span class="badge status-submitted">Ожидает</span>
                            </div>
                            <a href="{{ route('jury.submissions.show', $submission) }}" class="btn btn-sm btn-secondary rounded-pill px-3 ms-3">
                                Проверить
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-4 text-secondary text-center">Нет работ на проверке</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Требуют доработки</h6>
                <a href="{{ route('jury.submissions.index', ['status' => 'needs_fix']) }}" class="btn btn-sm btn-dark rounded-pill px-3">
                    Все {{ $stats['needs_fix'] }}
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($needsFixSubmissions as $submission)
                    <div class="list-item px-4 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">
                                    <a href="{{ route('jury.submissions.show', $submission) }}" class="text-decoration-none text-dark">
                                        {{ $submission->title }}
                                    </a>
                                </div>
                                <div class="small text-secondary mb-2">
                                    <span class="me-3"><i class="bi bi-person me-1"></i> {{ $submission->user->name }}</span>
                                    <span><i class="bi bi-trophy me-1"></i> {{ $submission->contest->title }}</span>
                                </div>
                                <span class="badge status-needs_fix">На доработке</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-4 text-secondary text-center">Нет работ на доработке</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Моя статистика -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Моя статистика</h6>
            </div>
            <div class="card-body px-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">Принято мной</div>
                            <div class="h3 fw-light">{{ $myStats['accepted'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">Отклонено мной</div>
                            <div class="h3 fw-light">{{ $myStats['rejected'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">На доработку</div>
                            <div class="h3 fw-light">{{ $myStats['needs_fix'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="text-secondary small mb-1">Всего проверено</div>
                            <div class="h3 fw-light">{{ $myStats['accepted'] + $myStats['rejected'] + $myStats['needs_fix'] }}</div>
                        </div>
                    </div>
                </div>
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

.progress {
    background: #e9ecef;
    border-radius: 999px;
}

.border-bottom {
    border-bottom: 1px solid #e9ecef !important;
}

.text-warning {
    color: #ffc107 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
}

.btn-dark {
    background-color: #212529;
    border-color: #212529;
}

.btn-dark:hover {
    background-color: #1a1e21;
    border-color: #1a1e21;
}
</style>
@endsection