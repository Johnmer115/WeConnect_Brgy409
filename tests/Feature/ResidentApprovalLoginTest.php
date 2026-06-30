<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentApprovalLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_registration_does_not_log_user_in_until_admin_approval(): void
    {
        $this->post(route('register.store'), [
            'username' => 'pendingresident',
            'email' => 'pending@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'last_name' => 'Santos',
            'first_name' => 'Ana',
            'date_of_birth' => '2000-01-01',
            'gender' => 'Female',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertGuest();

        $residentUser = User::where('username', 'pendingresident')->firstOrFail();
        $this->assertSame('inactive', $residentUser->status);

        $this->post(route('login.attempt'), [
            'account' => 'pendingresident',
            'password' => 'password123',
        ])
            ->assertSessionHasErrors('account');

        $this->assertGuest();

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'role' => 'secretary',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.residents.verify', $residentUser->resident))
            ->assertSessionHas('success');

        $residentUser->refresh();
        $this->assertSame('active', $residentUser->status);
        $this->assertNotNull($residentUser->resident->verified_at);
        $this->assertSame($admin->id, $residentUser->resident->verified_by);

        $this->post(route('logout'));

        $this->post(route('login.attempt'), [
            'account' => 'pendingresident',
            'password' => 'password123',
        ])->assertRedirect('/home');

        $this->assertAuthenticatedAs($residentUser);
    }

    public function test_approved_resident_can_log_in(): void
    {
        $residentUser = User::create([
            'name' => 'Approved Resident',
            'username' => 'approvedresident',
            'email' => null,
            'password' => 'password123',
            'role' => 'resident',
            'status' => 'active',
        ]);

        Resident::create([
            'user_id' => $residentUser->id,
            'first_name' => 'Ben',
            'last_name' => 'Reyes',
            'date_of_birth' => '1995-01-01',
            'gender' => 'Male',
            'health_status' => 'Alive',
            'verified_at' => now(),
            'verified_by' => $residentUser->id,
        ]);

        $this->post(route('login.attempt'), [
            'account' => 'approvedresident',
            'password' => 'password123',
        ])->assertRedirect('/home');

        $this->assertAuthenticatedAs($residentUser);
    }
}
