<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = [
        'user_id',
        'last_name', 'first_name', 'middle_name', 'suffix',
        'date_of_birth', 'blood_type', 'gender', 'religion',
        'health_status', 'date_deceased',
        'is_4ps', 'is_pwd', 'is_voter', 'is_single_parent',
        'email', 'telephone_number', 'mobile_number',
        'home_address', 'purok_id',
        'verified_at', 'verified_by',
    ];

    protected $casts = [
        'date_of_birth'  => 'date',
        'date_deceased'  => 'date',
        'verified_at'    => 'datetime',
        'is_4ps'         => 'boolean',
        'is_pwd'         => 'boolean',
        'is_voter'       => 'boolean',
        'is_single_parent' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purok()
    {
        return $this->belongsTo(Purok::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Computed full name helper
    public function getFullNameAttribute(): string
    {
        return collect([$this->first_name, $this->middle_name, $this->last_name, $this->suffix])
            ->filter()
            ->implode(' ');
    }

    // Age computed from DOB
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }
}
