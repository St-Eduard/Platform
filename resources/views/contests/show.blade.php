@extends('layouts.app')

@section('title', $contest->title)

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-trophy me-2"></i>
                    <h5 class="fw-light mb-0">{{ $contest->title }}</h5>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="small text-secondary me-3">
                        <i class="bi bi-calendar me-1"></i> Дедлайн: {{ $contest->deadline_at->format('d.m.Y H:i') }}
                    </div>
                    @if($contest->is_active)
                        <span class="badge bg-white text-dark border rounded-pill px-3 py-1">Активен</span>
                    @else
                        <span class="badge bg-white text-secondary border rounded-pill px-3 py-1">Завершен</span>
                    @endif
                </div>
                
                <h6 class="fw-medium mb-2">Описание конкурса:</h6>
                <p class="text-secondary mb-4">{{ $contest->description }}</p>
                
                <hr class="opacity-25">
                
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small">Участников:</span>
                        <span class="fw-medium ms-1">{{ $contest->submissions->count() }}</span>
                    </div>
                    
                    @auth
                        @if(auth()->user()->isParticipant() && $contest->is_active)
                            <a href="{{ route('participant.submissions.create', ['contest_id' => $contest->id]) }}" 
                               class="btn btn-dark rounded-pill px-4">
                                <i class="bi bi-plus me-1"></i> Участвовать
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle me-2"></i>
                    <h6 class="fw-light mb-0">Информация</h6>
                </div>
            </div>
            <div class="card-body px-4">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 pb-1 d-flex">
                        <i class="bi bi-calendar-check text-secondary me-3"></i>
                        <div>
                            <div class="small text-secondary">Дата создания</div>
                            <div class="fw-medium">{{ $contest->created_at->format('d.m.Y') }}</div>
                        </div>
                    </li>
                    <li class="mb-3 pb-1 d-flex">
                        <i class="bi bi-clock text-secondary me-3"></i>
                        <div>
                            <div class="small text-secondary">Дедлайн</div>
                            <div class="fw-medium">{{ $contest->deadline_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-people text-secondary me-3"></i>
                        <div>
                            <div class="small text-secondary">Участников</div>
                            <div class="fw-medium">{{ $contest->submissions->count() }}</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection