@extends('layouts.app')

@section('title', 'Вход в систему')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-light">Добро пожаловать</h3>
                        <p class="text-muted small">Войдите в свой аккаунт</p>
                    </div>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-medium text-secondary">Email</label>
                            <input type="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
                            @error('email')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label small fw-medium text-secondary">Пароль</label>
                                <a href="#" class="text-decoration-none small">Забыли?</a>
                            </div>
                            <input type="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                                   id="password" name="password" required placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-secondary" for="remember">
                                    Запомнить меня
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Войти
                            </button>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <span class="small text-muted">Нет аккаунта? </span>
                            <a href="{{ route('register') }}" class="text-decoration-none small fw-medium">Зарегистрироваться</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection