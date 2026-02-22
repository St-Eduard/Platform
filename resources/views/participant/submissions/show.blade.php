@extends('layouts.app')

@section('title', $submission->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary btn-sm rounded-pill px-3">
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
                            @case('draft') Черновик @break
                            @case('submitted') На проверке @break
                            @case('needs_fix') Доработка @break
                            @case('accepted') Принята @break
                            @case('rejected') Отклонена @break
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="mb-4 small text-secondary">
                    <i class="bi bi-trophy me-1"></i> Конкурс: {{ $submission->contest->title }}
                </div>
                
                <h6 class="fw-medium mb-2">Описание</h6>
                <p class="text-secondary mb-4">{{ $submission->description }}</p>
                
                <h6 class="fw-medium mb-3">Файлы</h6>
                <div class="files-list" id="filesList">
                    @foreach($submission->attachments as $attachment)
                        <div class="file-item" data-id="{{ $attachment->id }}">
                            <div class="file-info">
                                <i class="bi bi-file-earmark text-secondary"></i>
                                <div>
                                    <div class="file-name">{{ $attachment->original_name }}</div>
                                    <div class="file-meta">
                                        {{ round($attachment->size / 1024, 1) }} KB
                                        @if($attachment->status == 'scanned')
                                            <span class="badge bg-white text-success border border-success">Проверен</span>
                                        @elseif($attachment->status == 'rejected')
                                            <span class="badge bg-white text-danger border border-danger">Отклонен</span>
                                            @if($attachment->rejection_reason)
                                                <div class="text-danger small mt-1">{{ $attachment->rejection_reason }}</div>
                                            @endif
                                        @else
                                            <span class="badge bg-white text-secondary border">В очереди</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('attachments.download', $attachment) }}" class="btn btn-sm btn-secondary rounded-pill px-3" title="Скачать">
                                    <i class="bi bi-download"></i>
                                </a>
                                @if($submission->canBeEdited() && $attachment->status != 'scanned')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3 delete-file ms-1" data-id="{{ $attachment->id }}" title="Удалить">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($submission->canBeEdited())
                    <div class="upload-section mt-4">
                        <h6 class="fw-medium mb-2">Загрузить файл</h6>
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="file" class="form-control bg-light border-0" accept=".pdf,.zip,.png,.jpg">
                            <div class="progress mt-2 d-none" style="height: 4px;">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: 0%"></div>
                            </div>
                        </form>
                        <small class="text-muted">Максимум 3 файла, не более 10MB каждый</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Комментарии</h6>
            </div>
            <div class="card-body px-4">
                <div class="comments-list mb-4">
                    @forelse($submission->comments as $comment)
                        <div class="comment">
                            <div class="comment-header">
                                <span class="comment-author">{{ $comment->user->name }}</span>
                                <span class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <p class="mb-0 small">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="text-muted text-center small py-3">Нет комментариев</p>
                    @endforelse
                </div>

                @if($submission->canBeEdited())
                    <form action="{{ route('participant.submissions.comment', $submission) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control bg-light border-0" id="comment" name="comment" rows="2" placeholder="Добавить комментарий..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm rounded-pill px-4">
                            <i class="bi bi-chat me-1"></i> Отправить
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h6 class="fw-light mb-0">Действия</h6>
            </div>
            <div class="card-body px-4">
                @if($submission->canBeEdited())
                    <a href="{{ route('participant.submissions.edit', $submission) }}" class="btn btn-outline-dark w-100 mb-2 rounded-pill">
                        <i class="bi bi-pencil me-1"></i> Редактировать
                    </a>
                    
                    @if($submission->hasScannedAttachments())
                        <form action="{{ route('participant.submissions.submit', $submission) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark w-100 rounded-pill">
                                <i class="bi bi-send me-1"></i> Отправить на проверку
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 rounded-pill" disabled>
                            <i class="bi bi-send me-1"></i> Нужен проверенный файл
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.file-info i {
    font-size: 1.25rem;
}

.file-name {
    font-weight: 500;
    color: #212529;
    font-size: 0.9375rem;
}

.file-meta {
    font-size: 0.875rem;
    color: #6c757d;
}

.comment {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.comment-author {
    font-weight: 500;
    color: #212529;
}

.comment-date {
    color: #6c757d;
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
    height: 4px;
    border-radius: 2px;
    background: #e9ecef;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    $('#file').on('change', function() {
        let file = this.files[0];
        if (!file) return;
        
        if (file.size > 10 * 1024 * 1024) {
            alert('Файл не должен превышать 10MB');
            this.value = '';
            return;
        }
        
        let formData = new FormData();
        formData.append('file', file);
        
        $.ajax({
            url: '{{ route("attachments.upload", $submission) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('.progress').removeClass('d-none');
                $('.progress-bar').css('width', '0%');
            },
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        $('.progress-bar').css('width', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.error || 'Ошибка загрузки');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Ошибка загрузки файла';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                } else if (xhr.status === 413) {
                    errorMsg = 'Файл слишком большой';
                } else if (xhr.status === 500) {
                    errorMsg = 'Ошибка сервера';
                }
                alert(errorMsg);
                
                $('.progress').addClass('d-none');
                $('.progress-bar').css('width', '0%');
                $('#file').val('');
            }
        });
    });

    $('.delete-file').on('click', function() {
        if (!confirm('Удалить файл?')) return;
        
        let fileId = $(this).data('id');
        
        $.ajax({
            url: '/attachments/' + fileId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                location.reload();
            },
            error: function(xhr) {
                let errorMsg = 'Ошибка удаления файла';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                alert(errorMsg);
            }
        });
    });
});
</script>
@endpush
@endsection