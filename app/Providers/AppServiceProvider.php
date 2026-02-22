<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрируем сервисы
        $this->app->singleton(\App\Services\SubmissionService::class, function ($app) {
            return new \App\Services\SubmissionService();
        });
        
        $this->app->singleton(\App\Services\AttachmentService::class, function ($app) {
            return new \App\Services\AttachmentService();
        });
    }

    public function boot(): void
    {
        //
    }
}