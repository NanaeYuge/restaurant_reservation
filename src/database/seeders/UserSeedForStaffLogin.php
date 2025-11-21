<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeedForStaffLogin extends Seeder
{
    public function run(): void
    {
        // --- roles ---
        DB::table('roles')->updateOrInsert(['name' => 'admin'], ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('roles')->updateOrInsert(['name' => 'owner'], ['name' => 'owner', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('roles')->updateOrInsert(['name' => 'user'],  ['name' => 'user',  'created_at' => now(), 'updated_at' => now()]);

        $roleAdminId = DB::table('roles')->where('name','admin')->value('id');
        $roleOwnerId = DB::table('roles')->where('name','owner')->value('id');

        // --- users (idempotent) ---
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name'       => 'Admin User',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',          // usersテーブルにrole列がある前提
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
        DB::table('users')->updateOrInsert(
            ['email' => 'owner@example.com'],
            [
                'name'       => 'Owner User',
                'password'   => Hash::make('password123'),
                'role'       => 'owner',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $adminId = DB::table('users')->where('email','admin@example.com')->value('id');
        $ownerId = DB::table('users')->where('email','owner@example.com')->value('id');

        // --- role_user (重複せずに紐付け) ---
        if ($roleAdminId && $adminId) {
            DB::table('role_user')->updateOrInsert(
                ['role_id' => $roleAdminId, 'user_id' => $adminId],
                ['role_id' => $roleAdminId, 'user_id' => $adminId]
            );
        }
        if ($roleOwnerId && $ownerId) {
            DB::table('role_user')->updateOrInsert(
                ['role_id' => $roleOwnerId, 'user_id' => $ownerId],
                ['role_id' => $roleOwnerId, 'user_id' => $ownerId]
            );
        }
    }
}
