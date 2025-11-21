<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyBookingUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'テストユーザー1', 'email' => 'user1@example.com'],
            ['name' => 'テストユーザー2', 'email' => 'user2@example.com'],
            ['name' => 'テストユーザー3', 'email' => 'user3@example.com'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
