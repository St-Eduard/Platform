@extends('layouts.app')

@section('title', 'Редактирование пользователя')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 px-4 py-3">
                <h5 class="fw-light mb-0">
                    <i class="bi bi-pencil me-2"></i>{{ $user->name }}
                </h5>
            </div>
            <div class="card-body px-4">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label small text-secondary">Имя</label>
                        <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small text-secondary">Email</label>
                        <input type="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small text-secondary">Новый пароль</label>
                        <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Оставьте пустым, чтобы не менять">
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label small text-secondary">Роль</label>
                        <select class="form-control bg-light border-0 @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                            <option value="jury" {{ $user->role == 'jury' ? 'selected' : '' }}>Жюри</option>
                            <option value="participant" {{ $user->role == 'participant' ? 'selected' : '' }}>Участник</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Назад
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection