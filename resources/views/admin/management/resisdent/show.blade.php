@extends('admin.layouts.layout')

@section('title', 'Resident Details — WeConnect')
@section('page-title', 'Resident Details')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/resident-show.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 px-4">
    {{-- ── Profile Header Card ── --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">

                {{-- Avatar --}}
                <div class="rs-avatar flex-shrink-0">
                    {{ strtoupper(substr($resident->first_name, 0, 1) . substr($resident->last_name, 0, 1)) }}
                </div>

                {{-- Identity --}}
                <div class="flex-grow-1">
                    <p class="text-muted text-uppercase small fw-semibold mb-1 ls-wide">Resident Record</p>
                    <h5 class="fw-bold mb-1">{{ $resident->full_name }}</h5>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        {{-- Purok badge --}}
                        <span class="badge rounded-pill bg-primary-subtle text-primary fw-semibold">
                            <i class="fas fa-map-marker-alt fa-fw me-1"></i>
                            {{ $resident->purok->name ?? 'Unassigned' }}
                        </span>
                        {{-- Health status --}}
                        <span class="badge rounded-pill
                            {{ $resident->health_status === 'Alive' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}
                            fw-semibold">
                            <i class="fas fa-circle fa-fw me-1" style="font-size:.5rem;vertical-align:middle;"></i>
                            {{ $resident->health_status }}
                        </span>
                        {{-- Verification status --}}
                        @if ($resident->verified_at)
                            <span class="badge rounded-pill bg-success-subtle text-success fw-semibold">
                                <i class="fas fa-check-circle fa-fw me-1"></i> Verified
                            </span>
                        @else
                            <span class="badge rounded-pill bg-warning-subtle text-warning fw-semibold">
                                <i class="fas fa-clock fa-fw me-1"></i> Pending Verification
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-shrink-0">
                    @unless ($resident->verified_at)
                        <form method="POST" action="{{ route('admin.residents.verify', $resident) }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-check fa-fw me-1"></i> Accept
                            </button>
                        </form>
                    @endunless
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left fa-fw me-1"></i> Back
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Detail Cards Row ── --}}
    <div class="row g-4">

        {{-- LEFT — Basic Information + Memberships --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-4">

            {{-- Basic Information --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom px-4 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="fas fa-id-card fa-fw me-2 text-primary"></i> Basic Information
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">

                        <div class="col-6 col-md-4">
                            <p class="rs-label">Last Name</p>
                            <p class="rs-value">{{ $resident->last_name }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">First Name</p>
                            <p class="rs-value">{{ $resident->first_name }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Middle Name</p>
                            <p class="rs-value">{{ $resident->middle_name ?? '—' }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Suffix</p>
                            <p class="rs-value">{{ $resident->suffix ?? 'N/A' }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Date of Birth</p>
                            <p class="rs-value">{{ $resident->date_of_birth?->format('F d, Y') }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Age</p>
                            <p class="rs-value">{{ $resident->age }} yrs old</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Gender</p>
                            <p class="rs-value">{{ $resident->gender }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Blood Type</p>
                            <p class="rs-value">{{ $resident->blood_type ?? '—' }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <p class="rs-label">Religion</p>
                            <p class="rs-value">{{ $resident->religion ?? '—' }}</p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Memberships --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom px-4 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="fas fa-tags fa-fw me-2 text-primary"></i> Memberships
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-2">

                        <span class="badge rounded-pill px-3 py-2 fw-normal
                            {{ $resident->is_4ps ? 'bg-success text-white' : 'bg-light text-muted' }}">
                            <i class="fas fa-{{ $resident->is_4ps ? 'check' : 'times' }} fa-fw me-1"></i>
                            Pantawid Pamilyang Pilipino (4Ps)
                        </span>
                        <span class="badge rounded-pill px-3 py-2 fw-normal
                            {{ $resident->is_pwd ? 'bg-success text-white' : 'bg-light text-muted' }}">
                            <i class="fas fa-{{ $resident->is_pwd ? 'check' : 'times' }} fa-fw me-1"></i>
                            Person with Disability (PWD)
                        </span>
                        <span class="badge rounded-pill px-3 py-2 fw-normal
                            {{ $resident->is_voter ? 'bg-success text-white' : 'bg-light text-muted' }}">
                            <i class="fas fa-{{ $resident->is_voter ? 'check' : 'times' }} fa-fw me-1"></i>
                            Comelec Registered Voter
                        </span>
                        <span class="badge rounded-pill px-3 py-2 fw-normal
                            {{ $resident->is_single_parent ? 'bg-success text-white' : 'bg-light text-muted' }}">
                            <i class="fas fa-{{ $resident->is_single_parent ? 'check' : 'times' }} fa-fw me-1"></i>
                            Single Parent
                        </span>

                    </div>
                </div>
            </div>

        </div>
        {{-- /LEFT --}}

        {{-- RIGHT — Contact + System Info --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- Contact & Address --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom px-4 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="fas fa-address-book fa-fw me-2 text-primary"></i> Contact & Address
                    </h6>
                </div>
                <div class="card-body p-4 d-flex flex-column gap-3">

                    <div>
                        <p class="rs-label">Home Address</p>
                        <p class="rs-value">{{ $resident->home_address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="rs-label">Purok</p>
                        <p class="rs-value">{{ $resident->purok->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="rs-label">Mobile Number</p>
                        <p class="rs-value">{{ $resident->mobile_number ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="rs-label">Telephone</p>
                        <p class="rs-value">{{ $resident->telephone_number ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="rs-label">Email Address</p>
                        <p class="rs-value">{{ $resident->email ?? '—' }}</p>
                    </div>

                </div>
            </div>

            {{-- System Info --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom px-4 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="fas fa-info-circle fa-fw me-2 text-primary"></i> System Info
                    </h6>
                </div>
                <div class="card-body p-4 d-flex flex-column gap-3">

                    <div>
                        <p class="rs-label">Login Account</p>
                        <p class="rs-value">{{ $resident->user?->username ?? 'No login account' }}</p>
                    </div>
                    <div>
                        <p class="rs-label">Registered On</p>
                        <p class="rs-value">{{ $resident->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="rs-label">Verified On</p>
                        <p class="rs-value">
                            @if ($resident->verified_at)
                                {{ $resident->verified_at->format('F d, Y') }}
                                <span class="text-muted small d-block">
                                    by {{ $resident->verifiedBy?->name ?? '—' }}
                                </span>
                            @else
                                <span class="text-warning fw-semibold">Not yet verified</span>
                            @endif
                        </p>
                    </div>

                </div>
            </div>

        </div>
        {{-- /RIGHT --}}

    </div>
</div>
@endsection