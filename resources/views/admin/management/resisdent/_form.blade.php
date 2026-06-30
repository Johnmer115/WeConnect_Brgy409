@csrf

<div class="form-section">
    <h2 class="form-section-title">Basic Information</h2>
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $resident->first_name) }}" class="form-control @error('first_name') is-invalid @enderror">
            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}" class="form-control @error('middle_name') is-invalid @enderror">
            @error('middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $resident->last_name) }}" class="form-control @error('last_name') is-invalid @enderror">
            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-3">
            <label for="suffix" class="form-label">Suffix</label>
            <select id="suffix" name="suffix" class="form-select @error('suffix') is-invalid @enderror">
                <option value="">None</option>
                @foreach (['Jr.', 'Sr.', 'II', 'III', 'IV', 'V', 'VI'] as $suffixOption)
                    <option value="{{ $suffixOption }}" @selected(old('suffix', $resident->suffix) === $suffixOption)>{{ $suffixOption }}</option>
                @endforeach
            </select>
            @error('suffix') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-3">
            <label for="date_of_birth" class="form-label">Birth Date <span class="text-danger">*</span></label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $resident->date_of_birth?->format('Y-m-d')) }}" class="form-control @error('date_of_birth') is-invalid @enderror">
            @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-3">
            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                <option value="">Select gender</option>
                @foreach (['Male', 'Female'] as $gender)
                    <option value="{{ $gender }}" @selected(old('gender', $resident->gender) === $gender)>{{ $gender }}</option>
                @endforeach
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-3">
            <label for="blood_type" class="form-label">Blood Type</label>
            <select id="blood_type" name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                <option value="">Unknown</option>
                @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bloodType)
                    <option value="{{ $bloodType }}" @selected(old('blood_type', $resident->blood_type) === $bloodType)>{{ $bloodType }}</option>
                @endforeach
            </select>
            @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="religion" class="form-label">Religion</label>
            <select id="religion" name="religion" class="form-select @error('religion') is-invalid @enderror">
                <option value="">Select religion</option>
                @foreach ([
                    'Roman Catholic',
                    'Iglesia ni Cristo',
                    'Islam',
                    'Born Again Christian',
                    'Jehovah\'s Witnesses',
                    'Seventh-day Adventist',
                    'Baptist',
                    'Methodist',
                    'None / Others'
                ] as $religionOption)
                    <option value="{{ $religionOption }}" @selected(old('religion', $resident->religion) === $religionOption)>{{ $religionOption }}</option>
                @endforeach
            </select>
            @error('religion') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="health_status" class="form-label">Health Status <span class="text-danger">*</span></label>
            <select id="health_status" name="health_status" class="form-select @error('health_status') is-invalid @enderror">
                @foreach (['Alive', 'Deceased'] as $status)
                    <option value="{{ $status }}" @selected(old('health_status', $resident->health_status ?? 'Alive') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('health_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4" id="deceased_date_group">
            <label for="date_deceased" class="form-label">Date Deceased</label>
            <input type="date" id="date_deceased" name="date_deceased" value="{{ old('date_deceased', $resident->date_deceased?->format('Y-m-d')) }}" class="form-control @error('date_deceased') is-invalid @enderror">
            @error('date_deceased') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Date Deceased Toggle ──────────────────────────────────────
    const healthStatusSelect = document.getElementById('health_status');
    const deceasedDateGroup = document.getElementById('deceased_date_group');
    const deceasedDateInput = document.getElementById('date_deceased');

    function toggleDeceasedDate() {
        if (healthStatusSelect.value === 'Deceased') {
            deceasedDateGroup.style.display = '';
        } else {
            deceasedDateGroup.style.display = 'none';
            deceasedDateInput.value = '';
        }
    }

    if (healthStatusSelect && deceasedDateGroup) {
        healthStatusSelect.addEventListener('change', toggleDeceasedDate);
        toggleDeceasedDate();
    }

    // ── Draft Retention / Auto-Save ────────────────────────────────
    const form = document.querySelector('form[action*="residents"]');
    if (form && !form.querySelector('input[name="_method"]')) { // Only run on Create form (no PUT/PATCH method)
        const storageKey = 'resident_form_draft';

        // Save form inputs to localStorage
        function saveDraft() {
            const formData = {};
            const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="password"]):not([type="file"]), select, textarea');
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    formData[input.name] = input.checked;
                } else {
                    formData[input.name] = input.value;
                }
            });
            localStorage.setItem(storageKey, JSON.stringify(formData));
        }

        // Load form inputs from localStorage
        function loadDraft() {
            const savedData = localStorage.getItem(storageKey);
            if (!savedData) return;
            try {
                const formData = JSON.parse(savedData);
                Object.keys(formData).forEach(name => {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (!input) return;
                    if (input.type === 'checkbox') {
                        input.checked = formData[name];
                    } else {
                        input.value = formData[name];
                    }
                });
                // Re-run toggle logic in case health status was loaded from draft
                toggleDeceasedDate();
            } catch (e) {
                console.error('Failed to restore form draft:', e);
            }
        }

        // Load draft on load
        loadDraft();

        // Save draft on any input change
        form.addEventListener('input', saveDraft);
        form.addEventListener('change', saveDraft);

        // Clear draft when form is submitted
        form.addEventListener('submit', function () {
            localStorage.removeItem(storageKey);
        });

        // Clear draft when cancel button is clicked
        const cancelBtn = form.querySelector('.btn-outline-secondary, .btn-secondary');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                localStorage.removeItem(storageKey);
            });
        }
    }
});
</script>
@endpush

<div class="form-section">
    <h2 class="form-section-title">Contact and Address</h2>
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $resident->mobile_number) }}" class="form-control @error('mobile_number') is-invalid @enderror">
            @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="telephone_number" class="form-label">Telephone Number</label>
            <input type="text" id="telephone_number" name="telephone_number" value="{{ old('telephone_number', $resident->telephone_number) }}" class="form-control @error('telephone_number') is-invalid @enderror">
            @error('telephone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $resident->email) }}" class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="purok_id" class="form-label">Purok</label>
            <select id="purok_id" name="purok_id" class="form-select @error('purok_id') is-invalid @enderror">
                <option value="">Unassigned</option>
                @foreach ($puroks as $purok)
                    <option value="{{ $purok->id }}" @selected((string) old('purok_id', $resident->purok_id) === (string) $purok->id)>{{ $purok->name }}</option>
                @endforeach
            </select>
            @error('purok_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-8">
            <label for="home_address" class="form-label">Home Address</label>
            <input type="text" id="home_address" name="home_address" value="{{ old('home_address', $resident->home_address) }}" class="form-control @error('home_address') is-invalid @enderror">
            <div class="form-text small">Purok and Barangay 409, Manila City are added automatically.</div>
            @error('home_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="form-section">
    <h2 class="form-section-title">Categories</h2>
    <div class="resident-check-grid">
        @foreach ([
            'is_4ps' => '4Ps Member',
            'is_pwd' => 'Person with Disability',
            'is_voter' => 'Registered Voter',
            'is_single_parent' => 'Single Parent',
        ] as $field => $label)
            <label class="resident-check">
                <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $resident->{$field}))>
                <span>{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>
