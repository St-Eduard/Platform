@extends('layouts.app')

@section('title', 'Создание конкурса')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h5 class="fw-light mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Создание нового конкурса
                </h5>
            </div>
            <div class="card-body px-4">
                <form method="POST" action="{{ route('admin.contests.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label small text-secondary">Название конкурса</label>
                        <input type="text" class="form-control bg-light border-0 @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required placeholder="Введите название конкурса">
                        @error('title')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small text-secondary">Описание</label>
                        <textarea class="form-control bg-light border-0 @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" required placeholder="Опишите условия конкурса">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deadline_at" class="form-label small text-secondary">Дедлайн</label>
                        <input type="datetime-local" class="form-control bg-light border-0 @error('deadline_at') is-invalid @enderror" 
                               id="deadline_at" name="deadline_at" value="{{ old('deadline_at') }}" required>
                        @error('deadline_at')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label small" for="is_active">Активен</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.contests.index') }}" class="btn btn-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Назад
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> Создать
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection