@extends('layouts.app')

@section('title', 'Моя страница')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-light mb-0">Моя страница</h1>
    </div>
</div>

<!-- Статистика -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-files text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Всего работ</div>
                    <div class="stat-value h3 fw-light">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-pencil text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">Черновики</div>
                    <div class="stat-value h3 fw-light">{{ $stats['draft'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="stat-icon me-3">
                    <i class="bi bi-hourglass-split text-secondary"></i>
                </div>
                <div>
                    <div class="stat-label small text-secondary">На проверке</div>
                    <div class="stat-value h3 fw-light">{{ $stats['submitted'] }}</div>
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
                    <div class="stat-label small text-secondary">Принято</div>
                    <div class="stat-value h3 fw-light">{{ $stats['accepted'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Детальная статистика -->
<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Распределение работ</h6>
                <span class="badge bg-white text-secondary border">всего {{ $stats['total'] }}</span>
            </div>
            <div class="card-body px-4">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary">Черновики</span>
                        <span class="fw-medium">{{ $stats['draft'] }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $draftPercent = $stats['total'] > 0 ? ($stats['draft'] / $stats['total']) * 100 : 0; @endphp
                        <div class="progress-bar bg-secondary" style="width: {{ $draftPercent }}%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary">На проверке</span>
                        <span class="fw-medium">{{ $stats['submitted'] }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $submittedPercent = $stats['total'] > 0 ? ($stats['submitted'] / $stats['total']) * 100 : 0; @endphp
                        <div class="progress-bar bg-warning" style="width: {{ $submittedPercent }}%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary">Принято</span>
                        <span class="fw-medium">{{ $stats['accepted'] }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $acceptedPercent = $stats['total'] > 0 ? ($stats['accepted'] / $stats['total']) * 100 : 0; @endphp
                        <div class="progress-bar bg-success" style="width: {{ $acceptedPercent }}%"></div>
                    </div>
                </div>
                
                <div class="mb-0">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary">Требуют доработки</span>
                        <span class="fw-medium">{{ $stats['needs_fix'] }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        @php $needsFixPercent = $stats['total'] > 0 ? ($stats['needs_fix'] / $stats['total']) * 100 : 0; @endphp
                        <div class="progress-bar" style="background: #ffc107; width: {{ $needsFixPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Статистика по статусам</h6>
            </div>
            <div class="card-body px-4">
                <div class="stats-grid">
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-secondary small" style="width: 100px;">Отклонено</div>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress" style="height: 6px;">
                                @php $rejectedPercent = $stats['total'] > 0 ? ($stats['rejected'] / $stats['total']) * 100 : 0; @endphp
                                <div class="progress-bar bg-danger" style="width: {{ $rejectedPercent }}%"></div>
                            </div>
                        </div>
                        <div class="fw-medium" style="width: 40px;">{{ $stats['rejected'] }}</div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-secondary small" style="width: 100px;">На доработке</div>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress" style="height: 6px;">
                                @php $needsFixPercent = $stats['total'] > 0 ? ($stats['needs_fix'] / $stats['total']) * 100 : 0; @endphp
                                <div class="progress-bar bg-warning" style="width: {{ $needsFixPercent }}%"></div>
                            </div>
                        </div>
                        <div class="fw-medium" style="width: 40px;">{{ $stats['needs_fix'] }}</div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-secondary small" style="width: 100px;">На проверке</div>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress" style="height: 6px;">
                                @php $submittedPercent = $stats['total'] > 0 ? ($stats['submitted'] / $stats['total']) * 100 : 0; @endphp
                                <div class="progress-bar bg-warning" style="width: {{ $submittedPercent }}%"></div>
                            </div>
                        </div>
                        <div class="fw-medium" style="width: 40px;">{{ $stats['submitted'] }}</div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="text-secondary small" style="width: 100px;">Принято</div>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress" style="height: 6px;">
                                @php $acceptedPercent = $stats['total'] > 0 ? ($stats['accepted'] / $stats['total']) * 100 : 0; @endphp
                                <div class="progress-bar bg-success" style="width: {{ $acceptedPercent }}%"></div>
                            </div>
                        </div>
                        <div class="fw-medium" style="width: 40px;">{{ $stats['accepted'] }}</div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Процент успешных работ</span>
                        <span class="fw-light h4 mb-0">
                            @php $successRate = $stats['total'] > 0 ? round(($stats['accepted'] / $stats['total']) * 100) : 0; @endphp
                            {{ $successRate }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $successRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Последние работы -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-light mb-0">Последние работы</h6>
                <a href="{{ route('participant.submissions.index') }}" class="btn btn-sm btn-secondary rounded-pill px-3">
                    Все работы
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($recentSubmissions as $submission)
                    <div class="list-item px-4 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-medium mb-1">
                                    <a href="{{ route('participant.submissions.show', $submission) }}" class="text-decoration-none text-dark">
                                        {{ $submission->title }}
                                    </a>
                                </div>
                                <div class="small text-secondary">
                                    <span class="me-3"><i class="bi bi-trophy me-1"></i> {{ $submission->contest->title }}</span>
                                    <span class="me-3"><i class="bi bi-calendar me-1"></i> {{ $submission->created_at->format('d.m.Y') }}</span>
                                    <span><i class="bi bi-files me-1"></i> {{ $submission->attachments->count() }} файлов</span>
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
                    </div>
                @empty
                    <div class="px-4 py-4 text-secondary text-center">У вас пока нет работ</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Быстрые действия</h6>
            </div>
            <div class="card-body px-4">
                <a href="{{ route('participant.submissions.create') }}" class="btn btn-dark rounded-pill px-4 me-2">
                    <i class="bi bi-plus-circle me-1"></i> Новая работа
                </a>
                <a href="{{ route('contests.index') }}" class="btn btn-secondary rounded-pill px-4">
                    <i class="bi bi-trophy me-1"></i> Все конкурсы
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

.border-top {
    border-top: 1px solid #e9ecef !important;
}

.border-bottom {
    border-bottom: 1px solid #e9ecef !important;
}
</style>
@endsection