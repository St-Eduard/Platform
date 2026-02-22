@extends('layouts.app')

@section('title', $submission->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('jury.submissions.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Назад к списку
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-light mb-0">{{ $submission->title }}</h5>
                    <span class="badge status-{{ $submission->status }}">
                        @switch($submission->status)
                            @case('submitted') На проверке @break
                            @case('needs_fix') Требуется доработка @break
                            @case('accepted') Принята @break
                            @case('rejected') Отклонена @break
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="small text-secondary mb-2">Информация об участнике:</h6>
                        <div class="small">
                            <div class="mb-1"><strong class="text-dark">Имя:</strong> {{ $submission->user->name }}</div>
                            <div class="mb-1"><strong class="text-dark">Email:</strong> {{ $submission->user->email }}</div>
                            <div><strong class="text-dark">Участник с:</strong> {{ $submission->user->created_at->format('d.m.Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="small text-secondary mb-2">Информация о конкурсе:</h6>
                        <div class="small">
                            <div class="mb-1"><strong class="text-dark">Название:</strong> {{ $submission->contest->title }}</div>
                            <div class="mb-1"><strong class="text-dark">Дедлайн:</strong> {{ $submission->contest->deadline_at->format('d.m.Y H:i') }}</div>
                            <div>
                                <strong class="text-dark">Статус:</strong>
                                @if($submission->contest->is_active)
                                    <span class="badge bg-white text-success border border-success ms-1">Активен</span>
                                @else
                                    <span class="badge bg-white text-secondary border ms-1">Неактивен</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="small text-secondary mb-2">Описание работы:</h6>
                <p class="text-secondary small mb-4">{{ $submission->description }}</p>
                
                <h6 class="small text-secondary mb-3">Прикрепленные файлы:</h6>
                <div class="files-list">
                    @foreach($submission->attachments as $attachment)
                        <div class="file-item mb-2 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-file-earmark me-2 text-secondary"></i>
                                    <span class="fw-medium">{{ $attachment->original_name }}</span>
                                    <span class="text-secondary small ms-2">({{ round($attachment->size / 1024, 1) }} KB)</span>
                                    <div class="mt-1">
                                        @if($attachment->status === 'scanned')
                                            <span class="badge bg-white text-success border border-success">Проверен</span>
                                        @elseif($attachment->status === 'rejected')
                                            <span class="badge bg-white text-danger border border-danger">Отклонен</span>
                                            @if($attachment->rejection_reason)
                                                <small class="text-danger d-block mt-1">{{ $attachment->rejection_reason }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-white text-secondary border">В очереди</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-sm btn-secondary rounded-pill px-3">
                                    <i class="bi bi-download me-1"></i> Скачать
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Комментарии</h6>
            </div>
            <div class="card-body px-4">
                <div class="comments-list mb-4">
                    @forelse($submission->comments as $comment)
                        <div class="comment mb-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="small">{{ $comment->user->name }}</strong>
                                <small class="text-secondary">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                            <p class="small text-secondary mb-0">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="text-secondary text-center small py-3">Пока нет комментариев</p>
                    @endforelse
                </div>

                <form action="{{ route('jury.submissions.comment', $submission) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control bg-light border-0" id="comment" name="comment" rows="2" placeholder="Добавить комментарий..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill px-4">
                        <i class="bi bi-chat me-1"></i> Отправить
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Действия жюри</h6>
            </div>
            <div class="card-body px-4">
                @if(in_array($submission->status, ['submitted', 'needs_fix']))
                    <form action="{{ route('jury.submissions.status', $submission) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn btn-success w-100 rounded-pill" 
                                onclick="return confirm('Принять работу?')">
                            <i class="bi bi-check-circle me-1"></i> Принять
                        </button>
                    </form>

                    <form action="{{ route('jury.submissions.status', $submission) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="needs_fix">
                        <button type="submit" class="btn btn-warning w-100 rounded-pill" 
                                onclick="return confirm('Запросить доработку?')">
                            <i class="bi bi-arrow-repeat me-1"></i> Запросить доработку
                        </button>
                    </form>

                    <form action="{{ route('jury.submissions.status', $submission) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill" 
                                onclick="return confirm('Отклонить работу?')">
                            <i class="bi bi-x-circle me-1"></i> Отклонить
                        </button>
                    </form>
                @else
                    <p class="text-secondary small text-center mb-3">Работа уже обработана</p>
                    <a href="{{ route('jury.submissions.index') }}" class="btn btn-dark w-100 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> К списку
                    </a>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Информация</h6>
            </div>
            <div class="card-body px-4">
                <ul class="list-unstyled small">
                    <li class="mb-3 d-flex">
                        <i class="bi bi-calendar text-secondary me-3"></i>
                        <div>
                            <div class="text-secondary">Создана</div>
                            <div class="fw-medium">{{ $submission->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </li>
                    <li class="mb-3 d-flex">
                        <i class="bi bi-clock-history text-secondary me-3"></i>
                        <div>
                            <div class="text-secondary">Обновлена</div>
                            <div class="fw-medium">{{ $submission->updated_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </li>
                    <li class="d-flex">
                        <i class="bi bi-files text-secondary me-3"></i>
                        <div>
                            <div class="text-secondary">Всего файлов</div>
                            <div class="fw-medium">{{ $submission->attachments->count() }}</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
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

.file-item {
    transition: background-color 0.15s;
}

.comment {
    transition: background-color 0.15s;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background-color: #ffca2c;
    border-color: #ffc720;
    color: #212529;
}

.btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: #ffffff;
}
</style>
@endsection