<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AreaSeeder::class,
            GenreSeeder::class,
            ShopSeeder::class,
            AdminUserSeeder::class,
            OwnerUserSeeder::class,
            DummyBookingUsersSeeder::class,
        ]);
    }
}
