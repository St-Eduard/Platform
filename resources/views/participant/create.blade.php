@extends('layouts.app')

@section('title', 'Создание работы')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h5 class="fw-light mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Создание новой работы
                </h5>
            </div>
            <div class="card-body px-4">
                <form method="POST" action="{{ route('participant.submissions.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="contest_id" class="form-label small text-secondary">Конкурс</label>
                        <select class="form-control bg-light border-0 @error('contest_id') is-invalid @enderror" 
                                id="contest_id" name="contest_id" required>
                            <option value="">Выберите конкурс</option>
                            @foreach($contests as $contest)
                                <option value="{{ $contest->id }}" {{ old('contest_id') == $contest->id ? 'selected' : '' }}>
                                    {{ $contest->title }} (до {{ $contest->deadline_at->format('d.m.Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('contest_id')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label small text-secondary">Название работы</label>
                        <input type="text" class="form-control bg-light border-0 @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required placeholder="Введите название работы">
                        @error('title')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label small text-secondary">Описание</label>
                        <textarea class="form-control bg-light border-0 @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" required placeholder="Опишите вашу работу">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert bg-light border-0 small text-secondary mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        После создания черновика вы сможете загрузить файлы.<br>
                        <span class="text-muted">Работа должна содержать хотя бы один проверенный файл перед отправкой.</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('participant.submissions.index') }}" class="btn btn-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Назад
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> Создать черновик
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection