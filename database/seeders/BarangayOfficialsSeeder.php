<?php

namespace Database\Seeders;

use App\Models\BarangayOfficial;
use Illuminate\Database\Seeder;

class BarangayOfficialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing officials first to avoid duplicates
        BarangayOfficial::truncate();

        // Barangay Officials
        $barangayOfficials = [
            ['name' => 'Brenda S. Puertollano', 'position' => 'Chairman', 'category' => 'barangay', 'sort_order' => 1],
            ['name' => 'Jeffrey L. Salcedo', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 2],
            ['name' => 'Ma. Gienel N. Fontanilla', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 3],
            ['name' => 'Alfredo C. Regino Jr.', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 4],
            ['name' => 'Ruel P. Cabrera', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 5],
            ['name' => 'Rafaelito C. Apanay', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 6],
            ['name' => 'Kevin Michael C. Batac', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 7],
            ['name' => 'Edric Justin Y. Makainag', 'position' => 'Kgd.', 'category' => 'barangay', 'sort_order' => 8],
            ['name' => 'Reynaldo A. Camacho', 'position' => 'Treasurer', 'category' => 'barangay', 'sort_order' => 9],
            ['name' => 'Ma. Veronica M. Pajares', 'position' => 'Secretary', 'category' => 'barangay', 'sort_order' => 10],
        ];

        foreach ($barangayOfficials as $official) {
            BarangayOfficial::create($official);
        }
    }
}
