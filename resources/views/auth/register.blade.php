@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-light">Создать аккаунт</h3>
                        <p class="text-muted small">Заполните форму для регистрации</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-medium text-secondary">Имя</label>
                            <input type="text" class="form-control bg-light border-0 @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Иван Петров">
                            @error('name')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-medium text-secondary">Email</label>
                            <input type="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required placeholder="ivan@example.com">
                            @error('email')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-medium text-secondary">Пароль</label>
                            <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                                   id="password" name="password" required placeholder="Минимум 8 символов">
                            @error('password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label small fw-medium text-secondary">Подтверждение пароля</label>
                            <input type="password" class="form-control bg-light border-0" 
                                   id="password-confirm" name="password_confirmation" required placeholder="Повторите пароль">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark py-2">
                                <i class="bi bi-person-plus me-2"></i> Зарегистрироваться
                            </button>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <span class="small text-muted">Уже есть аккаунт? </span>
                            <a href="{{ route('login') }}" class="text-decoration-none small fw-medium">Войти</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection