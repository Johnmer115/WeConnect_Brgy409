<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper to check role from views/controllers, e.g. auth()->user()->isAdmin()
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['secretary', 'chairman', 'kagawad']);
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    public function getAccountTypeAttribute(): string
    {
        return $this->isResident() ? 'User' : 'Admin';
    }

    public function getPositionLabelAttribute(): string
    {
        return match ($this->role) {
            'chairman' => 'Barangay Chairman',
            'secretary' => 'Barangay Councilor',
            'kagawad' => 'Barangay SK Councilor',
            'resident' => 'Resident',
            default => ucfirst($this->role ?? 'Unknown'),
        };
    }

    public function resident()
    {
        return $this->hasOne(Resident::class);
    }
}
