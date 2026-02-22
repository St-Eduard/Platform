<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Админ', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::create(['name' => 'Жюри', 'email' => 'jury@test.com', 'password' => Hash::make('password'), 'role' => 'jury']);
        User::create(['name' => 'Участник', 'email' => 'participant@test.com', 'password' => Hash::make('password'), 'role' => 'participant']);

        Contest::create([
            'title' => 'Конкурс дизайна 2024',
            'description' => 'Лучший веб-дизайн',
            'deadline_at' => now()->addDays(30),
            'is_active' => true
        ]);
    }
}