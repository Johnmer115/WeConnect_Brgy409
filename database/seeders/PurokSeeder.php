<?php

namespace Database\Seeders;

use App\Models\Purok;
use Illuminate\Database\Seeder;

class PurokSeeder extends Seeder
{
    public function run(): void
    {
        $puroks = [
            ['name' => 'Purok 1', 'color_code' => '#4A90D9'],
            ['name' => 'Purok 2', 'color_code' => '#27AE60'],
            ['name' => 'Purok 3', 'color_code' => '#E67E22'],
            ['name' => 'Purok 4', 'color_code' => '#8E44AD'],
            ['name' => 'Purok 5', 'color_code' => '#E74C3C'],
            ['name' => 'Purok 6', 'color_code' => '#F39C12'],
            ['name' => 'Purok 7', 'color_code' => '#1ABC9C'],
        ];

        foreach ($puroks as $purok) {
            Purok::create($purok);
        }
    }
}