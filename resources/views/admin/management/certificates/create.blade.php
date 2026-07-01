@extends('admin.layouts.layout')

@section('title', 'Issue Certificate - WeConnect Brgy 409')
@section('page-title', 'Issue Certificate')

@section('content')
<div class="container-fluid admin-page cert-page">

    <div class="cert-header">
        <div>
            <h1 class="cert-title">Issue New Certificate</h1>
            <p class="cert-subtitle">Search a resident to auto-fill, or fill in the form manually</p>
        </div>
        <a href="{{ route('admin.certificates.index') }}" class="cert-btn-back">
            <i class="fas fa-arrow-left fa-fw"></i>
            Back to List
        </a>
    </div>

    {{-- ── Resident Search (Autocomplete Combobox) ──────────────── --}}
    <div class="cert-lookup-card" id="cert-lookup-card">
        <div class="cert-lookup-header">
            <i class="fas fa-search fa-fw"></i>
            <span>Search Resident Record</span>
            <span class="cert-lookup-hint">&mdash; optional, auto-fills the form below</span>
        </div>
        <div class="cert-lookup-body">
            <div class="cert-combobox" id="cert-combobox">
                <input type="text"
                       id="resident-search"
                       class="cert-lookup-input"
                       placeholder="Click to select or search resident record…"
                       autocomplete="off"
                       spellcheck="false"
                       role="combobox"
                       aria-expanded="false"
                       aria-haspopup="listbox"
                       aria-controls="resident-dropdown">
                <div class="cert-combobox-icon" id="lookup-spinner" hidden>
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <div class="cert-combobox-icon cert-combobox-chevron" id="lookup-clear-icon" hidden>
                    <button type="button" id="lookup-clear" title="Clear selection" aria-label="Clear">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="cert-combobox-icon cert-combobox-chevron" id="lookup-chevron-icon">
                    <i class="fas fa-chevron-down text-muted" style="font-size: 0.8rem; cursor: pointer;"></i>
                </div>
                <ul class="cert-dropdown" id="resident-dropdown" role="listbox" hidden></ul>
            </div>

            {{-- Selected badge --}}
            <div class="cert-lookup-selected" id="lookup-selected" hidden>
                <i class="fas fa-user-check fa-fw"></i>
                <span id="lookup-selected-name"></span>
                <span class="cert-linked-badge">Linked to Resident Record</span>
            </div>
        </div>
    </div>

    {{-- ── Certificate Form ──────────────────────────────────────── --}}
    <form method="POST"
          action="{{ route('admin.certificates.store') }}"
          class="cert-form-card"
          id="cert-issue-form"
          novalidate>
        @csrf
        <input type="hidden" name="resident_id" id="field-resident-id" value="">

        {{-- ── Section 1 : Basic Information ─────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-user fa-fw"></i> Basic Information
            </h2>
            <div class="cert-form-row cert-form-row-4">
                <div class="cert-field">
                    <label class="cert-label" for="f-last-name">Last Name <span class="cert-required">*</span></label>
                    <input type="text" class="cert-input @error('last_name') is-invalid @enderror"
                           id="f-last-name" name="last_name"
                           value="{{ old('last_name') }}" required placeholder="e.g. Dela Cruz">
                    @error('last_name')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-first-name">First Name <span class="cert-required">*</span></label>
                    <input type="text" class="cert-input @error('first_name') is-invalid @enderror"
                           id="f-first-name" name="first_name"
                           value="{{ old('first_name') }}" required placeholder="e.g. Juan">
                    @error('first_name')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-middle-name">Middle Name</label>
                    <input type="text" class="cert-input"
                           id="f-middle-name" name="middle_name"
                           value="{{ old('middle_name') }}" placeholder="e.g. Santos">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-suffix">Suffix</label>
                    <input type="text" class="cert-input"
                           id="f-suffix" name="suffix"
                           value="{{ old('suffix') }}" placeholder="e.g. Jr.">
                </div>
            </div>
            <div class="cert-form-row cert-form-row-4">
                <div class="cert-field">
                    <label class="cert-label" for="f-dob">Date of Birth</label>
                    <input type="date" class="cert-input"
                           id="f-dob" name="date_of_birth"
                           value="{{ old('date_of_birth') }}">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-age">Age</label>
                    <input type="number" class="cert-input"
                           id="f-age" name="age" min="0" max="150"
                           value="{{ old('age') }}" placeholder="e.g. 25">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-gender">Gender</label>
                    <select class="cert-input cert-select-field" id="f-gender" name="gender">
                        <option value="" @selected(!old('gender'))>— Select —</option>
                        <option value="Male"   @selected(old('gender') === 'Male')>Male</option>
                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                    </select>
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-religion">Religion</label>
                    <input type="text" class="cert-input"
                           id="f-religion" name="religion"
                           value="{{ old('religion') }}" placeholder="e.g. Roman Catholic">
                </div>
            </div>
        </div>

        <div class="cert-form-divider"></div>

        {{-- ── Section 2 : Home Address ────────────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-map-marker-alt fa-fw"></i> Home Address Information
            </h2>
            <div class="cert-form-row">
                <div class="cert-field">
                    <label class="cert-label" for="f-address">Home Address <span class="cert-required">*</span></label>
                    <input type="text" class="cert-input @error('address') is-invalid @enderror"
                           id="f-address" name="address"
                           value="{{ old('address') }}" required placeholder="e.g. 1234 Oroquieta Street">
                    @error('address')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="cert-form-row cert-form-row-3">
                <div class="cert-field">
                    <label class="cert-label" for="f-purok">Purok</label>
                    <input type="text" class="cert-input"
                           id="f-purok" name="purok"
                           value="{{ old('purok') }}" placeholder="e.g. Purok 1">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-barangay-city">Barangay / City</label>
                    <input type="text" class="cert-input"
                           id="f-barangay-city" name="barangay_city"
                           value="{{ old('barangay_city', 'Barangay 409, Manila City') }}"
                           placeholder="e.g. Barangay 409, Manila City">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-country">Country</label>
                    <input type="text" class="cert-input"
                           id="f-country" name="country"
                           value="{{ old('country', 'Philippines') }}" placeholder="e.g. Philippines">
                </div>
            </div>
        </div>

        <div class="cert-form-divider"></div>

        {{-- ── Section 3 : Contact Information ────────────────────── --}}
        <div class="cert-form-section-wrap">
            <h2 class="cert-form-section-title">
                <i class="fas fa-phone fa-fw"></i> Contact Information
            </h2>
            <div class="cert-form-row cert-form-row-3">
                <div class="cert-field">
                    <label class="cert-label" for="f-email">Email Address</label>
                    <input type="email" class="cert-input @error('email') is-invalid @enderror"
                           id="f-email" name="email"
                           value="{{ old('email') }}" placeholder="e.g. name@example.com">
                    @error('email')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-telephone">Telephone Number</label>
                    <input type="text" class="cert-input"
                           id="f-telephone" name="telephone"
                           value="{{ old('telephone') }}" placeholder="e.g. (02) 8123-4567">
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-mobile">Mobile Number</label>
                    <input type="text" class="cert-input"
                           id="f-mobile" name="mobile"
                           value="{{ old('mobile') }}" placeholder="e.g. 09123456789">
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
                        <option value="" disabled @selected(!old('certificate_type'))>— Select Type —</option>
                        @foreach($types as $val => $label)
                            <option value="{{ $val }}" @selected(old('certificate_type') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('certificate_type')<div class="cert-error">{{ $message }}</div>@enderror
                </div>
                <div class="cert-field">
                    <label class="cert-label" for="f-purpose">Purpose of Printing</label>
                    <input type="text" class="cert-input"
                           id="f-purpose" name="purpose"
                           value="{{ old('purpose') }}" placeholder="e.g. Employment, Scholarship…">
                </div>
            </div>
        </div>

        {{-- ── Form Actions ─────────────────────────────────────────── --}}
        <div class="cert-form-actions">
            <a href="{{ route('admin.certificates.index') }}" class="cert-btn-cancel">Cancel</a>
            <button type="submit" class="cert-btn-submit">
                <i class="fas fa-save fa-fw"></i> Issue Certificate
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var LOOKUP_URL = '{{ route('admin.certificates.residentLookup') }}';

    // ── DOM refs ─────────────────────────────────────────────────
    var searchInput   = document.getElementById('resident-search');
    var spinner       = document.getElementById('lookup-spinner');
    var dropdown      = document.getElementById('resident-dropdown');
    var clearIcon     = document.getElementById('lookup-clear-icon');
    var chevronIcon   = document.getElementById('lookup-chevron-icon');
    var clearBtn      = document.getElementById('lookup-clear');
    var selectedBox   = document.getElementById('lookup-selected');
    var selectedName  = document.getElementById('lookup-selected-name');
    var residentId    = document.getElementById('field-resident-id');

    // Form fields map  { jsonKey: fieldId }
    var FIELD_MAP = {
        last_name:     'f-last-name',
        first_name:    'f-first-name',
        middle_name:   'f-middle-name',
        suffix:        'f-suffix',
        date_of_birth: 'f-dob',
        age:           'f-age',
        gender:        'f-gender',
        religion:      'f-religion',
        address:       'f-address',
        purok:         'f-purok',
        barangay_city: 'f-barangay-city',
        country:       'f-country',
        email:         'f-email',
        telephone:     'f-telephone',
        mobile:        'f-mobile',
    };

    var debounceTimer = null;
    var isSelected = false;

    // ── Focus & Click handlers for instant dropdown ──────────────
    searchInput.addEventListener('focus', function () {
        if (isSelected) return;
        doSearch(this.value.trim());
    });

    searchInput.addEventListener('click', function () {
        if (isSelected) return;
        if (dropdown.hidden) {
            doSearch(this.value.trim());
        }
    });

    // ── Chevron Click handler ────────────────────────────────────
    if (chevronIcon) {
        chevronIcon.addEventListener('click', function (e) {
            e.stopPropagation();
            if (isSelected) return;
            searchInput.focus();
            if (dropdown.hidden) {
                doSearch(searchInput.value.trim());
            } else {
                hideDropdown();
            }
        });
    }

    // ── Typing handler ───────────────────────────────────────────
    searchInput.addEventListener('input', function () {
        var q = this.value.trim();

        // If user types after selection, clear it
        if (isSelected) clearSelection(false);

        clearTimeout(debounceTimer);
        hideDropdown();

        debounceTimer = setTimeout(function () { doSearch(q); }, 300);
    });

    // ── AJAX search ──────────────────────────────────────────────
    function doSearch(q) {
        spinner.hidden = false;
        if (chevronIcon) chevronIcon.hidden = true;
        fetch(LOOKUP_URL + '?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            spinner.hidden = true;
            if (!isSelected && chevronIcon) chevronIcon.hidden = false;
            renderDropdown(data);
        })
        .catch(function () {
            spinner.hidden = true;
            if (!isSelected && chevronIcon) chevronIcon.hidden = false;
        });
    }

    // ── Render dropdown ──────────────────────────────────────────
    function renderDropdown(items) {
        dropdown.innerHTML = '';

        if (items.length === 0) {
            var li = document.createElement('li');
            li.className = 'cert-dropdown-item cert-dropdown-empty';
            li.setAttribute('role', 'option');
            li.setAttribute('aria-disabled', 'true');
            li.textContent = 'No record found. Fill in manually.';
            dropdown.appendChild(li);
        } else {
            items.forEach(function (r) {
                var li = document.createElement('li');
                li.className = 'cert-dropdown-item';
                li.setAttribute('role', 'option');
                li.innerHTML =
                    '<span class="cert-dd-name">' + escHtml(r.full_name) + '</span>' +
                    '<span class="cert-dd-address">' + escHtml(r.full_address || r.address || '') + '</span>';
                li.addEventListener('mousedown', function (e) {
                    e.preventDefault(); // prevent blur
                    selectResident(r);
                });
                dropdown.appendChild(li);
            });
        }

        dropdown.hidden = false;
        searchInput.setAttribute('aria-expanded', 'true');
    }

    // ── Select a resident ────────────────────────────────────────
    function selectResident(r) {
        isSelected = true;
        residentId.value = r.id;
        searchInput.value = r.full_name;

        // Auto-fill all matching form fields
        Object.keys(FIELD_MAP).forEach(function (key) {
            var el = document.getElementById(FIELD_MAP[key]);
            if (!el) return;
            var val = r[key] !== undefined ? r[key] : '';
            el.value = val;
            if (val) el.classList.add('cert-autofilled');
        });

        // Show selected badge
        selectedName.textContent = r.full_name;
        selectedBox.hidden = false;
        clearIcon.hidden = false;
        if (chevronIcon) chevronIcon.hidden = true;

        hideDropdown();
    }

    // ── Clear selection ──────────────────────────────────────────
    function clearSelection(clearInput) {
        isSelected = false;
        residentId.value = '';

        Object.keys(FIELD_MAP).forEach(function (key) {
            var el = document.getElementById(FIELD_MAP[key]);
            if (el) el.classList.remove('cert-autofilled');
        });

        selectedBox.hidden = true;
        clearIcon.hidden = true;
        if (chevronIcon) chevronIcon.hidden = false;

        if (clearInput !== false) {
            searchInput.value = '';
            // Also blank filled fields
            Object.keys(FIELD_MAP).forEach(function (key) {
                var el = document.getElementById(FIELD_MAP[key]);
                if (el) el.value = key === 'barangay_city' ? 'Barangay 409, Manila City'
                                 : key === 'country'       ? 'Philippines'
                                 : '';
            });
        }
    }

    // ── Hide dropdown ────────────────────────────────────────────
    function hideDropdown() {
        dropdown.hidden = true;
        dropdown.innerHTML = '';
        searchInput.setAttribute('aria-expanded', 'false');
    }

    // ── Clear button ─────────────────────────────────────────────
    clearBtn.addEventListener('click', function () { clearSelection(true); });

    // ── Close on outside click ───────────────────────────────────
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#cert-combobox')) hideDropdown();
    });

    // ── Auto-calculate age from DOB ───────────────────────────────
    document.getElementById('f-dob').addEventListener('change', function () {
        if (!this.value) return;
        var today = new Date();
        var dob   = new Date(this.value);
        var age   = today.getFullYear() - dob.getFullYear();
        var m     = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
        document.getElementById('f-age').value = age >= 0 ? age : '';
    });

    // ── Helper: escape HTML ───────────────────────────────────────
    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
})();
</script>
@endpush
