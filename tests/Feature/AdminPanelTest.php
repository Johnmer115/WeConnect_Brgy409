<?php

namespace Tests\Feature;

use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dashboard_residents_and_accounts_pages(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'secretary',
            'status' => 'active',
        ]);

        $residentUser = User::create([
            'name' => 'Resident User',
            'username' => 'residentuser',
            'email' => null,
            'password' => 'password',
            'role' => 'resident',
            'status' => 'active',
        ]);

        $purok = Purok::create([
            'name' => 'Purok 1',
            'color_code' => '#4A90D9',
        ]);

        Resident::create([
            'user_id' => $residentUser->id,
            'first_name' => 'Ana',
            'last_name' => 'Santos',
            'date_of_birth' => '2000-01-01',
            'gender' => 'Female',
            'health_status' => 'Alive',
            'purok_id' => $purok->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Recent Residents')
            ->assertSee('Ana Santos');

        $this->actingAs($admin)
            ->get(route('admin.residents.index'))
            ->assertOk()
            ->assertSee('Resident Records')
            ->assertSee('Ana Santos');

        $this->actingAs($admin)
            ->get(route('admin.accounts.index'))
            ->assertOk()
            ->assertSee('Admin Accounts')
            ->assertSee('adminuser');
    }
}
