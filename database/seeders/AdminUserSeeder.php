<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void 
    {
     // Barangay Chairman
        User::create([
            'name'     => 'Juan Dela Cruz',
            'username' => 'chairman',
            'email'    => 'chairman@weconnect.test',
            'password' => Hash::make('password123'),
            'role'     => 'chairman',
            'status'   => 'active',
        ]);

        // Barangay Secretary
        User::create([
            'name'     => 'Maria Santos',
            'username' => 'secretary',
            'email'    => 'secretary@weconnect.test',
            'password' => Hash::make('password123'),
            'role'     => 'secretary',
            'status'   => 'active',
        ]);

        // Kagawad
        User::create([
            'name'     => 'Pedro Reyes',
            'username' => 'kagawad',
            'email'    => 'kagawad@weconnect.test',
            'password' => Hash::make('password123'),
            'role'     => 'kagawad',
            'status'   => 'active',
        ]);

        // Sample resident account (so you can test the resident-side redirect too)
        User::create([
            'name'     => 'Resident Test',
            'username' => 'resident',
            'email'    => 'resident@weconnect.test',
            'password' => Hash::make('password123'),
            'role'     => 'resident',
            'status'   => 'active',
        ]);
    }
}
