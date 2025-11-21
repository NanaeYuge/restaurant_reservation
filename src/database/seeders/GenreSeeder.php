<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['寿司','焼肉','居酒屋','イタリアン','ラーメン','カフェ'] as $name) {
            Genre::firstOrCreate(['name' => $name]);
        }
    }
}
