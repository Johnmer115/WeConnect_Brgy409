<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public const TYPES = [
        'barangay_clearance' => 'Barangay Clearance',
        'indigency'          => 'Certificate of Indigency',
        'residency'          => 'Certificate of Residency',
        'certification'      => 'Barangay Certification',
    ];

    public const STATUSES = [
        'pending'   => 'Pending',
        'ongoing'   => 'Ongoing',
        'completed' => 'Completed',
    ];

    public const GENDERS = ['Male', 'Female'];

    protected $fillable = [
        'resident_id',
        // Basic information
        'last_name', 'first_name', 'middle_name', 'suffix',
        'date_of_birth', 'age', 'gender', 'religion',
        // Home address
        'address', 'purok', 'barangay_city', 'country',
        // Contact information
        'email', 'telephone', 'mobile',
        // Certificate details
        'certificate_type', 'purpose', 'status',
        // Audit
        'issued_by', 'issued_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issued_at'     => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // ── Computed Attributes ───────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name]);
        $givenName = implode(' ', $parts);
        $name = trim("{$this->last_name}, {$givenName}");
        return implode(' ', array_filter([$name, $this->suffix]));
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->certificate_type] ?? $this->certificate_type;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->purok ? 'Purok ' . $this->purok : null,
            $this->barangay_city,
            $this->country,
        ])->filter()->implode(', ');
    }
}
