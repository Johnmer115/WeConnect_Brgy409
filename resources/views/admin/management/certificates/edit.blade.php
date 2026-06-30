@extends('admin.layouts.layout')

@section('title', 'Edit Certificate - WeConnect Brgy 409')
@section('page-title', 'Edit Certificate')

@section('content')
<div class="container-fluid admin-page cert-page">

    <div class="cert-header">
        <div>
            <h1 class="cert-title">Edit Certificate</h1>
            <p class="cert-subtitle">
                Editing record for
                <strong>{{ $certificate->full_name }}</strong>
                @if($certificate->resident_id)
                    &mdash; <span class="cert-linked-badge"><i class="fas fa-user-check fa-fw"></i> Linked to Resident Record</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.certificates.index') }}" class="cert-btn-back">
            <i class="fas fa-arrow-left fa-fw"></i>
            Back to List
        </a>
    </div>

    <form method="POST"
          action="{{ route('admin.certificates.update', $certificate) }}"
          class="cert-form-card"
          id="cert-edit-form"
          novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="resident_id" value="{{ $certificate->resident_id }}">

        {{-- ── Section 1 : Basic Information ──────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-user fa-fw"></i> Basic Information
            </h2>
            <div class="cert-form-row cert-form-row-4">
                <div class="cert-field">
                    <label class="cert-label" for="f-last-name">Last Name <span class="cert-required">*</span></label>
                    <input type="text" class="cert-input @error('last_name') is-invalid @enderror"
                           id="f-last-name" name="last_name"
                           value="{{ old('last_name', $certificate->last_name) }}"
                           required placeholder="Last Name">
                    @error('last_name')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-first-name">First Name <span class="cert-required">*</span></label>
                    <input type="text" class="cert-input @error('first_name') is-invalid @enderror"
                           id="f-first-name" name="first_name"
                           value="{{ old('first_name', $certificate->first_name) }}"
                           required placeholder="First Name">
                    @error('first_name')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-middle-name">Middle Name</label>
                    <input type="text" class="cert-input"
                           id="f-middle-name" name="middle_name"
                           value="{{ old('middle_name', $certificate->middle_name) }}"
                           placeholder="Middle Name">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-suffix">Suffix</label>
                    <input type="text" class="cert-input"
                           id="f-suffix" name="suffix"
                           value="{{ old('suffix', $certificate->suffix) }}"
                           placeholder="Jr., Sr., III…">
                </div>
            </div>
            <div class="cert-form-row cert-form-row-4">
                <div class="cert-field">
                    <label class="cert-label" for="f-dob">Date of Birth</label>
                    <input type="date" class="cert-input"
                           id="f-dob" name="date_of_birth"
                           value="{{ old('date_of_birth', $certificate->date_of_birth?->format('Y-m-d')) }}">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-age">Age</label>
                    <input type="number" class="cert-input"
                           id="f-age" name="age" min="0" max="150"
                           value="{{ old('age', $certificate->age) }}" placeholder="Age">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-gender">Gender</label>
                    <select class="cert-input cert-select-field" id="f-gender" name="gender">
                        <option value="" @selected(!old('gender', $certificate->gender))>— Select —</option>
                        <option value="Male"   @selected(old('gender', $certificate->gender) === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender', $certificate->gender) === 'Female')>Female</option>
                    </select>
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-religion">Religion</label>
                    <input type="text" class="cert-input"
                           id="f-religion" name="religion"
                           value="{{ old('religion', $certificate->religion) }}"
                           placeholder="Religion">
                </div>
            </div>
        </div>

        <div class="cert-form-divider"></div>

        {{-- ── Section 2 : Home Address ─────────────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-map-marker-alt fa-fw"></i> Home Address Information
            </h2>
            <div class="cert-form-row cert-form-row-2">
                <div class="cert-field">
                    <label class="cert-label" for="f-address">Home Address <span class="cert-required">*</span></label>
                    <input type="text" class="cert-input @error('address') is-invalid @enderror"
                           id="f-address" name="address"
                           value="{{ old('address', $certificate->address) }}"
                           required placeholder="Street / House No.">
                    @error('address')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-purok">Purok</label>
                    <input type="text" class="cert-input"
                           id="f-purok" name="purok"
                           value="{{ old('purok', $certificate->purok) }}"
                           placeholder="Purok name / number">
                </div>
            </div>
            <div class="cert-form-row cert-form-row-2">
                <div class="cert-field">
                    <label class="cert-label" for="f-barangay-city">Barangay / City</label>
                    <input type="text" class="cert-input"
                           id="f-barangay-city" name="barangay_city"
                           value="{{ old('barangay_city', $certificate->barangay_city ?? 'Barangay 409, Manila City') }}"
                           placeholder="Barangay / City">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-country">Country</label>
                    <input type="text" class="cert-input"
                           id="f-country" name="country"
                           value="{{ old('country', $certificate->country ?? 'Philippines') }}"
                           placeholder="Country">
                </div>
            </div>
        </div>

        <div class="cert-form-divider"></div>

        {{-- ── Section 3 : Contact Information ─────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-phone fa-fw"></i> Contact Information
            </h2>
            <div class="cert-form-row cert-form-row-3">
                <div class="cert-field">
                    <label class="cert-label" for="f-email">Email Address</label>
                    <input type="email" class="cert-input @error('email') is-invalid @enderror"
                           id="f-email" name="email"
                           value="{{ old('email', $certificate->email) }}"
                           placeholder="email@example.com">
                    @error('email')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-telephone">Telephone Number</label>
                    <input type="text" class="cert-input"
                           id="f-telephone" name="telephone"
                           value="{{ old('telephone', $certificate->telephone) }}"
                           placeholder="(02) 8xxx-xxxx">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-mobile">Mobile Number</label>
                    <input type="text" class="cert-input"
                           id="f-mobile" name="mobile"
                           value="{{ old('mobile', $certificate->mobile) }}"
                           placeholder="09xx-xxx-xxxx">
                </div>
            </div>
        </div>

        <div class="cert-form-divider"></div>

        {{-- ── Section 4 : Certificate Details ────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-file-signature fa-fw"></i> Certificate Details
            </h2>
            <div class="cert-form-row cert-form-row-3">
                <div class="cert-field">
                    <label class="cert-label" for="f-cert-type">Certificate Type <span class="cert-required">*</span></label>
                    <select class="cert-input cert-select-field @error('certificate_type') is-invalid @enderror"
                            id="f-cert-type" name="certificate_type" required>
                        @foreach($types as $val => $label)
                            <option value="{{ $val }}" @selected(old('certificate_type', $certificate->certificate_type) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('certificate_type')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-purpose">Purpose of Printing</label>
                    <input type="text" class="cert-input"
                           id="f-purpose" name="purpose"
                           value="{{ old('purpose', $certificate->purpose) }}"
                           placeholder="e.g. Employment, Scholarship…">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-status">Status <span class="cert-required">*</span></label>
                    <select class="cert-input cert-select-field @error('status') is-invalid @enderror"
                            id="f-status" name="status" required>
                        @foreach($statuses as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', $certificate->status) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ── Form Actions ─────────────────────────────────────────── --}}
        <div class="cert-form-actions">
            <a href="{{ route('admin.certificates.index') }}" class="cert-btn-cancel">Cancel</a>
            <button type="submit" class="cert-btn-submit">
                <i class="fas fa-save fa-fw"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';
    // Auto-calculate age from DOB on edit form too
    document.getElementById('f-dob').addEventListener('change', function () {
        if (!this.value) return;
        var today = new Date(), dob = new Date(this.value);
        var age = today.getFullYear() - dob.getFullYear();
        var m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
        document.getElementById('f-age').value = age >= 0 ? age : '';
    });
})();
</script>
@endpush
