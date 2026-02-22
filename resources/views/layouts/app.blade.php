<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Конкурсы') — Платформа</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Базовый сброс */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #ffffff;
            color: #212529;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Навигация */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 500;
            font-size: 1.25rem;
            color: #212529;
            text-decoration: none;
        }

        .navbar-brand:hover {
            color: #000000;
        }

        .nav-link {
            color: #6c757d;
            font-weight: 400;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #212529;
            background-color: #f8f9fa;
        }

        .nav-link.active {
            color: #000000;
            background-color: #f8f9fa;
        }

        /* Основной контент */
        main {
            flex: 1;
            padding: 2rem 0;
        }

        /* Карточки */
        .card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .card-header h5, .card-header h6 {
            margin: 0;
            font-weight: 400;
            color: #212529;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        /* Кнопки */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 400;
            border-radius: 0.375rem;
            transition: all 0.15s;
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 0.9375rem;
        }

        .btn-primary {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
        }

        .btn-primary:hover {
            background: #212529;
            border-color: #212529;
        }

        .btn-secondary {
            background: #ffffff;
            border-color: #dee2e6;
            color: #6c757d;
        }

        .btn-secondary:hover {
            background: #f8f9fa;
            border-color: #ced4da;
            color: #212529;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Таблицы */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            padding: 0.875rem 1rem;
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 500;
            font-size: 0.875rem;
            border-bottom: 1px solid #e9ecef;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            color: #212529;
        }

        /* Бейджи */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 400;
            line-height: 1.5;
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #e9ecef;
        }

        .badge-success {
            background: #f8f9fa;
            color: #198754;
            border-color: #d1e7dd;
        }

        .badge-warning {
            background: #f8f9fa;
            color: #ffc107;
            border-color: #fff3cd;
        }

        .badge-danger {
            background: #f8f9fa;
            color: #dc3545;
            border-color: #f8d7da;
        }

        .badge-info {
            background: #f8f9fa;
            color: #0dcaf0;
            border-color: #cff4fc;
        }

        /* Формы */
        .form-label {
            display: block;
            margin-bottom: 0.375rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.5rem 0.875rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            font-size: 0.9375rem;
            background: #ffffff;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #000000;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Уведомления */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            background: #ffffff;
            border-color: #e9ecef;
            color: #212529;
        }

        .alert-success {
            background: #f8f9fa;
            border-color: #d1e7dd;
            color: #198754;
        }

        .alert-danger {
            background: #f8f9fa;
            border-color: #f8d7da;
            color: #dc3545;
        }

        .alert-warning {
            background: #f8f9fa;
            border-color: #fff3cd;
            color: #856404;
        }

        /* Пагинация */
        .pagination {
            display: flex;
            gap: 0.25rem;
            list-style: none;
            justify-content: center;
        }

        .page-item .page-link {
            display: block;
            padding: 0.375rem 0.75rem;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .page-item.active .page-link {
            background: #000000;
            border-color: #000000;
            color: #ffffff;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
        }

        /* Прогресс бар */
        .progress {
            background: #e9ecef;
            border-radius: 9999px;
            height: 0.5rem;
            overflow: hidden;
        }

        .progress-bar {
            background: #000000;
            height: 100%;
        }

        /* Футер */
        .footer {
            background: #ffffff;
            border-top: 1px solid #e9ecef;
            padding: 1.5rem 0;
            color: #6c757d;
            font-size: 0.875rem;
        }

        /* Контейнер */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Статистика */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1.25rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 300;
            color: #212529;
            line-height: 1.2;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
        }

        /* Выпадающее меню */
        .dropdown-menu {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 0.375rem 0;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #212529;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        /* Списки */
        .list-item {
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item-title {
            font-weight: 500;
            color: #212529;
            margin-bottom: 0.25rem;
        }

        .list-item-meta {
            color: #6c757d;
            font-size: 0.875rem;
        }

        /* Утилиты */
        .text-muted {
            color: #6c757d;
        }

        .bg-light {
            background: #f8f9fa;
        }

        .border-light {
            border-color: #e9ecef;
        }

        .rounded {
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Конкурсы
            </a>
            
            <div class="d-flex align-items-center gap-2">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">Главная</a>
                        <a class="nav-link {{ request()->routeIs('admin.contests*') ? 'active' : '' }}" 
                           href="{{ route('admin.contests.index') }}">Конкурсы</a>
                        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                           href="{{ route('admin.users.index') }}">Пользователи</a>
                    @elseif(auth()->user()->isJury())
                        <a class="nav-link {{ request()->routeIs('jury.dashboard') ? 'active' : '' }}" 
                           href="{{ route('jury.dashboard') }}">Главная</a>
                        <a class="nav-link {{ request()->routeIs('jury.submissions*') ? 'active' : '' }}" 
                           href="{{ route('jury.submissions.index') }}">На проверку</a>
                    @else
                        <a class="nav-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }}" 
                           href="{{ route('participant.dashboard') }}">Главная</a>
                        <a class="nav-link {{ request()->routeIs('participant.submissions*') ? 'active' : '' }}" 
                           href="{{ route('participant.submissions.index') }}">Мои работы</a>
                    @endif

                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" style="gap: 0.25rem;">
                            {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                        Выйти
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Вход</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Регистрация</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">© {{ date('Y') }} Платформа для проведения конкурсов</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>