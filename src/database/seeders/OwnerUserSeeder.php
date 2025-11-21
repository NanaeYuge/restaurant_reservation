<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class OwnerUserSeeder extends Seeder {
    public function run(): void {
        User::updateOrCreate(
        ['email' => 'owner@example.com'],
        [
        'name' => 'オーナー',
        'password' => bcrypt('password'),
        'role' => 'owner',
        'email_verified_at' => now(),
        ]
    );
    }
}

