@extends('admin.layouts.navbar')

@section('sidebar')
    @include('resident.sidebar')
@endsection

@section('page-title', isset($resident) && $resident->verified_at ? 'Resident Portal' : 'Resident Details')

@section('content')
    @unless ($resident->verified_at)
        <div class="alert alert-warning border-0 shadow-sm small">
            <div class="fw-semibold mb-1">Pending admin approval</div>
            <div>
                You can open your account and view your submitted details. Announcements and Reports will be available after an admin approves your registration.
            </div>
        </div>
    @endunless

    @yield('resident-content')
@endsection
