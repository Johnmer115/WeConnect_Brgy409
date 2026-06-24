<?php

namespace Database\Seeders;

use App\Models\Purok;
use Illuminate\Database\Seeder;

class PurokSeeder extends Seeder
{
    public function run(): void
    {
        $puroks = [
            ['name' => 'Sunflower', 'color_code' => '#F4C542'],
            ['name' => 'Rosal', 'color_code' => '#FFFFFF'],
            ['name' => 'Gumamela', 'color_code' => '#E74C3C'],
            ['name' => 'Sampaguita', 'color_code' => '#DCEEFF'],
            ['name' => 'Ilang-Ilang', 'color_code' => '#27AE60'],
        ];

        foreach ($puroks as $purok) {
            Purok::updateOrCreate(
                ['name' => $purok['name']],
                ['color_code' => $purok['color_code']]
            );
        }
    }
}
