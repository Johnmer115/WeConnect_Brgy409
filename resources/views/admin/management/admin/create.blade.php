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
                    <label for="resident-search" class="form-label">Resident <span class="text-danger">*</span></label>
                    <div class="cert-combobox" id="resident-combobox" style="position: relative;">
                        <input type="text"
                               id="resident-search"
                               class="form-control @error('resident_id') is-invalid @enderror"
                               placeholder="Click to select or search resident…"
                               autocomplete="off"
                               spellcheck="false"
                               role="combobox"
                               aria-expanded="false"
                               aria-haspopup="listbox">
                        <input type="hidden" id="resident_id" name="resident_id" value="{{ old('resident_id') }}">
                        <div class="cert-combobox-icon cert-combobox-chevron" id="resident-chevron-icon" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>
                        <ul class="cert-dropdown" id="resident-dropdown-list" role="listbox" hidden style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #d1d9e6; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); list-style: none; padding: 4px 0; margin: 0; z-index: 1000; max-height: 200px; overflow-y: auto;">
                            @foreach ($availableResidents as $resident)
                                <li class="cert-dropdown-item"
                                    role="option"
                                    data-value="{{ $resident->id }}"
                                    data-name="{{ $resident->full_name }}"
                                    style="padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; font-size: 0.85rem;">
                                    <span class="fw-semibold text-dark">{{ $resident->full_name }}</span>
                                    <span class="text-muted small">{{ $resident->purok?->name ?? 'No Purok' }}</span>
                                </li>
                            @endforeach
                            @if ($availableResidents->isEmpty())
                                <li class="cert-dropdown-item text-muted text-center py-2" role="option" aria-disabled="true">No residents available</li>
                            @endif
                        </ul>
                    </div>
                    @error('resident_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
                // If it is the hidden input field resident_id, we do not disable it, so the old input and request still works.
                if (field.id === 'resident_id' && enabled) {
                    field.disabled = false;
                } else if (field.id !== 'resident-search') {
                    field.disabled = !enabled;
                }
            });
        }

        function syncMode() {
            const mode = typeSelect.value || form.dataset.startType || 'admin';
            setSectionState(sections.admin, mode === 'admin');
            setSectionState(sections.user, mode === 'user');
        }

        typeSelect.addEventListener('change', syncMode);
        syncMode();

        // ── Searchable Dropdown for Residents ──────────────────────────
        const searchInput = document.getElementById('resident-search');
        const hiddenInput = document.getElementById('resident_id');
        const dropdownList = document.getElementById('resident-dropdown-list');
        const chevronIcon = document.getElementById('resident-chevron-icon');

        if (searchInput && dropdownList && hiddenInput) {
            const items = dropdownList.querySelectorAll('li[data-value]');

            // Pre-fill if there is an old value
            const initialVal = hiddenInput.value;
            if (initialVal) {
                const match = Array.from(items).find(item => String(item.dataset.value) === String(initialVal));
                if (match) {
                    searchInput.value = match.dataset.name;
                }
            }

            function showDropdown() {
                dropdownList.hidden = false;
                searchInput.setAttribute('aria-expanded', 'true');
            }

            function hideDropdown() {
                dropdownList.hidden = true;
                searchInput.setAttribute('aria-expanded', 'false');
            }

            function filterItems() {
                const query = searchInput.value.toLowerCase().trim();
                items.forEach(item => {
                    const name = item.dataset.name.toLowerCase();
                    if (name.includes(query)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('focus', function () {
                showDropdown();
                filterItems();
            });

            searchInput.addEventListener('click', function () {
                if (dropdownList.hidden) {
                    showDropdown();
                    filterItems();
                }
            });

            searchInput.addEventListener('input', function () {
                showDropdown();
                filterItems();
                
                // Clear hidden ID if text doesn't exactly match a resident
                const match = Array.from(items).find(item => item.dataset.name.toLowerCase() === searchInput.value.toLowerCase().trim());
                if (match) {
                    hiddenInput.value = match.dataset.value;
                } else {
                    hiddenInput.value = '';
                }
            });

            if (chevronIcon) {
                chevronIcon.addEventListener('click', function (e) {
                    e.stopPropagation();
                    searchInput.focus();
                    if (dropdownList.hidden) {
                        showDropdown();
                        filterItems();
                    } else {
                        hideDropdown();
                    }
                });
            }

            items.forEach(item => {
                item.addEventListener('mousedown', function (e) {
                    e.preventDefault(); // prevents input blur from closing dropdown before select
                    searchInput.value = this.dataset.name;
                    hiddenInput.value = this.dataset.value;
                    hideDropdown();
                });
                item.addEventListener('mouseenter', function () {
                    this.style.background = '#f1f5f9';
                });
                item.addEventListener('mouseleave', function () {
                    this.style.background = '#fff';
                });
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('#resident-combobox')) {
                    hideDropdown();
                }
            });
        }
    })();
</script>
@endsection
