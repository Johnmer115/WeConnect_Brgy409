@extends('admin.layouts.layout')

@section('title', 'Certificate Form - WeConnect Brgy 409')
@section('page-title', 'Certificate Form')

@section('content')
<div class="container-fluid admin-page certificate-page">
    <div class="certificate-form-shell">
        <div class="certificate-bar">Certificate Form</div>

        <form class="certificate-form-grid">
            <section>
                <h2>Basic Information</h2>
                <label>Last Name</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->last_name }}" readonly>
                <label>First Name</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->first_name }}" readonly>
                <label>Middle Name</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->middle_name }}" readonly>
                <label>Suffix</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->suffix }}" readonly>
            </section>

            <section>
                <h2>&nbsp;</h2>
                <label>Date of Birth</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->date_of_birth?->format('m/d/Y') }}" readonly>
                <label>Age</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->age }}" readonly>
                <label>Gender</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->gender }}" readonly>
                <label>Religion</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->religion }}" readonly>
            </section>

            <section>
                <h2>Contact Information</h2>
                <label>Email Address</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->email }}" readonly>
                <label>Telephone Number</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->telephone_number }}" readonly>
                <label>Mobile Number</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->mobile_number }}" readonly>
            </section>

            <section>
                <h2>Home Address Information</h2>
                <label>Home Address</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->home_address }}" readonly>
                <label>Purok</label>
                <input type="text" class="form-control form-control-sm" value="{{ $resident->purok->name ?? 'Unassigned' }}" readonly>
                <label>Barangay / City</label>
                <input type="text" class="form-control form-control-sm" value="Barangay 409, Manila City" readonly>
            </section>

            <section class="certificate-print-panel">
                <h2>Print Certificate</h2>
                <label>Certificate Type</label>
                <select class="form-select form-select-sm" onchange="window.location='{{ route('admin.certificates.show', $resident) }}?type=' + this.value">
                    @foreach ($certificateTypes as $value => $label)
                        <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <label>Purpose of Printing</label>
                <input type="text" class="form-control form-control-sm" placeholder="Purpose of Printing">
                <div class="certificate-print-actions">
                    <button type="button" class="btn btn-sm btn-dark" onclick="window.print()">Certificate of Indigency</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">Certificate of Residency</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">{{ $selectedTypeLabel }}</button>
                </div>
            </section>
        </form>
    </div>
</div>
@endsection
