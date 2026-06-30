<?php

namespace Database\Seeders;

use App\Models\Purok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PurokSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Purok::truncate();
        Schema::enableForeignKeyConstraints();

        $puroks = [
            ['name' => 'Jasmin',     'color_code' => '#E0E0E0'], // light grey-white
            ['name' => 'Sampaguita', 'color_code' => '#F8F8F8'], // off-white
            ['name' => 'Sunflower',  'color_code' => '#F4C542'], // yellow
            ['name' => 'Dahlia',     'color_code' => '#D6336C'], // magenta/pink
            ['name' => 'Camia',      'color_code' => '#B5EAD7'], // soft mint (stem/leaf tint)
            ['name' => 'Rosal',      'color_code' => '#A0D8EF'], // soft sky blue (distinct accent)
            ['name' => 'Gumamela',   'color_code' => '#E74C3C'], // red
        ];

        foreach ($puroks as $purok) {
            Purok::create([
                'name' => $purok['name'],
                'color_code' => $purok['color_code']
            ]);
        }
    }
}
