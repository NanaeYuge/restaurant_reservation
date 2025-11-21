<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['東京都','大阪府','福岡県','北海道','愛知県'] as $name) {
            Area::firstOrCreate(['name' => $name]);
        }
    }
}
