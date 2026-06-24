@extends('admin.layouts.layout')

@section('title', 'Register Account - WeConnect Brgy 409')
@section('page-title', 'Register Account')

@section('content')
<div class="container-fluid admin-page">
    <form method="POST" action="{{ route('admin.accounts.store') }}" class="admin-form-panel" id="account-create-form" data-start-type="{{ old('account_type', 'admin') }}">
        @csrf

        <div class="form-panel-header">
            <div>
                <h1 class="form-panel-title">
                    <i class="fas fa-user-plus fa-fw"></i>
                    Register Account
                </h1>
                <p class="form-panel-subtitle">Create an admin account or connect a resident to a user login.</p>
            </div>
            <div class="form-panel-actions">
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary" title="Cancel">
                    <i class="fas fa-xmark fa-fw"></i>
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-floppy-disk fa-fw me-1"></i> Save Account
                </button>
            </div>
        </div>

        <div class="form-section">
            <h2 class="form-section-title">Account Type</h2>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label for="account_type" class="form-label">Type <span class="text-danger">*</span></label>
                    <select id="account_type" name="account_type" class="form-select @error('account_type') is-invalid @enderror">
                        <option value="">Select type</option>
                        @foreach ($accountTypeOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('account_type', 'admin') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-section" data-section="admin-fields">
            <h2 class="form-section-title">Admin Account Details</h2>
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label for="admin_name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="admin_name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Juan Dela Cruz">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="admin_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" id="admin_email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="name@example.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="admin_role" class="form-label">Position <span class="text-danger">*</span></label>
                    <select id="admin_role" name="role" class="form-select @error('role') is-invalid @enderror">
                        <option value="">Select position</option>
                        @foreach ($adminRoleOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="admin_username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" id="admin_username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="username">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="admin_password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" id="admin_password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label for="admin_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" id="admin_password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat password">
                </div>
            </div>
        </div>

        <div class="form-section" data-section="user-fields">
            <h2 class="form-section-title">Resident User Account</h2>
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-text mb-2">Choose a resident who does not yet have an account, then set the login credentials.</div>
                </div>
                <div class="col-12 col-md-6">
                    <label for="resident_id" class="form-label">Resident <span class="text-danger">*</span></label>
                    <select id="resident_id" name="resident_id" class="form-select @error('resident_id') is-invalid @enderror">
                        <option value="">Select resident</option>
                        @forelse ($availableResidents as $resident)
                            <option value="{{ $resident->id }}" @selected((string) old('resident_id') === (string) $resident->id)>
                                {{ $resident->full_name }}{{ $resident->purok?->name ? ' - ' . $resident->purok->name : '' }}
                            </option>
                        @empty
                            <option value="" disabled>No residents available</option>
                        @endforelse
                    </select>
                    @error('resident_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="resident_username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" id="resident_username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="username">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="resident_password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" id="resident_password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 col-md-6">
                    <label for="resident_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" id="resident_password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat password">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    (function () {
        const form = document.getElementById('account-create-form');
        if (!form) {
            return;
        }

        const typeSelect = document.getElementById('account_type');
        const sections = {
            admin: form.querySelector('[data-section="admin-fields"]'),
            user: form.querySelector('[data-section="user-fields"]'),
        };

        function setSectionState(section, enabled) {
            if (!section) {
                return;
            }

            section.hidden = !enabled;
            section.querySelectorAll('input, select, textarea').forEach(function (field) {
                field.disabled = !enabled;
            });
        }

        function syncMode() {
            const mode = typeSelect.value || form.dataset.startType || 'admin';
            setSectionState(sections.admin, mode === 'admin');
            setSectionState(sections.user, mode === 'user');
        }

        typeSelect.addEventListener('change', syncMode);
        syncMode();
    })();
</script>
@endsection
