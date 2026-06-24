@extends('loginpage.layout')

@section('content')
<style>
    .registration-card {
        max-width: 920px;
        font-size: 0.92rem;
    }

    .registration-card .card-body {
        padding: 1rem 1.15rem;
    }

    .registration-card .card-header {
        padding: 0.75rem 1rem;
    }

    .registration-card .form-label {
        margin-bottom: 0.35rem;
    }

    .registration-card .form-control,
    .registration-card .form-select,
    .registration-card .btn {
        min-height: 38px;
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
        font-size: 0.92rem;
    }

    .registration-card hr {
        margin: 1rem 0;
    }

    .membership-box {
        height: 100%;
    }

    .membership-box .form-check {
        display: flex;
        align-items: flex-start;
        gap: 0.45rem;
        min-height: 1.7rem;
        padding-left: 0;
    }

    .membership-box .form-check-input {
        flex: 0 0 auto;
        margin-left: 0;
        margin-top: 0.18rem;
    }

    .membership-box .form-check-label {
        line-height: 1.25;
    }

    .registration-step[hidden] {
        display: none;
    }
</style>

<section class="d-flex align-items-start justify-content-center py-3 px-3">
    <div class="card registration-card border-0 shadow-sm w-100">

        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-landmark me-2"></i> WeConnect — Resident Registration
            </h5>
            <a href="{{ route('login') }}" class="btn btn-sm btn-light">
                Back to Login
            </a>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $showAccountStep = $errors->has('username') || $errors->has('password');
            @endphp

            <form method="POST" action="{{ route('register.store') }}" id="resident-registration-form" data-draft-key="weconnect.resident-registration.draft" data-start-step="{{ $showAccountStep ? 'account' : 'details' }}">
                @csrf

                <div class="registration-step" data-step="details" {{ $showAccountStep ? 'hidden' : '' }}>
                {{-- ── BASIC INFORMATION ── --}}
                <p class="fw-semibold text-muted small text-uppercase mb-3">Basic Information</p>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" id="last_name" name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control @error('last_name') is-invalid @enderror"
                               placeholder="Last name">
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror">
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="blood_type" class="form-label">Blood Type</label>
                        <select id="blood_type" name="blood_type" class="form-select">
                            <option value="">-- Select --</option>
                            @foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bt)
                                <option value="{{ $bt }}" {{ old('blood_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control @error('first_name') is-invalid @enderror"
                               placeholder="First name">
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="age" class="form-label">Age</label>
                        <input type="text" id="age" class="form-control bg-light" placeholder="Age" readonly>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Health Status</label>
                        <input type="text" class="form-control bg-light" value="Alive" readonly>
                        <input type="hidden" name="health_status" value="Alive">
                    </div>
                </div>

                <div class="row g-3 mb-3 align-items-start">
                    <div class="col-12 col-md-8">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name"
                                       value="{{ old('middle_name') }}"
                                       class="form-control" placeholder="Middle name">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select id="gender" name="gender"
                                        class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">-- Select --</option>
                                    <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select id="suffix" name="suffix" class="form-select">
                                    <option value="">Choose... (N/A)</option>
                                    @foreach (['Jr.','Sr.','II','III','IV'] as $s)
                                        <option value="{{ $s }}" {{ old('suffix') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="religion" class="form-label">Religion</label>
                                <select id="religion" name="religion" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach (['Roman Catholic','Islam','Iglesia ni Cristo','Born Again Christian','Seventh Day Adventist','Other'] as $r)
                                        <option value="{{ $r }}" {{ old('religion') === $r ? 'selected' : '' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 membership-box">
                        <label class="form-label">Are you a member of the following:</label>
                        <div class="d-flex flex-column gap-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="1" {{ old('is_4ps') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_4ps">Pantawid Pamilyang Pilipino Program</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_pwd" id="is_pwd" value="1" {{ old('is_pwd') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_pwd">Person with Disability</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_voter" id="is_voter" value="1" {{ old('is_voter') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_voter">Comelec Registered Voter</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_single_parent" id="is_single_parent" value="1" {{ old('is_single_parent') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="is_single_parent">Single Parent</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- ── CONTACT INFORMATION ── --}}
                <p class="fw-semibold text-muted small text-uppercase mb-3">Contact Information</p>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Email Address">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="telephone_number" class="form-label">Telephone Number</label>
                        <input type="text" id="telephone_number" name="telephone_number"
                               value="{{ old('telephone_number') }}"
                               class="form-control" placeholder="Telephone Number">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="mobile_number" class="form-label">Mobile Number</label>
                        <input type="text" id="mobile_number" name="mobile_number"
                               value="{{ old('mobile_number') }}"
                               class="form-control" placeholder="Mobile Number">
                    </div>
                </div>

                <hr class="my-4">

                {{-- ── HOME ADDRESS INFORMATION ── --}}
                <p class="fw-semibold text-muted small text-uppercase mb-3">Home Address Information</p>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="home_address" class="form-label">Home Address</label>
                        <input type="text" id="home_address" name="home_address"
                               value="{{ old('home_address') }}"
                               class="form-control" placeholder="House No., Street">
                        <div class="form-text small">Purok and Barangay 409, Manila City are added automatically.</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="purok_id" class="form-label">Purok</label>
                        <select id="purok_id" name="purok_id" class="form-select @error('purok_id') is-invalid @enderror">
                            <option value="">Choose Purok</option>
                            @foreach ($puroks as $purok)
                                <option value="{{ $purok->id }}" @selected((string) old('purok_id') === (string) $purok->id)>{{ $purok->name }}</option>
                            @endforeach
                        </select>
                        @error('purok_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Barangay / City</label>
                        <input type="text" class="form-control bg-light" value="Barangay 409, Manila City" readonly>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Country</label>
                        <input type="text" class="form-control bg-light" value="Philippines" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 pt-2">
                    <button type="button" id="next-step" class="btn btn-success px-5 py-2">Next Step</button>
                    <button type="reset" class="btn btn-warning px-5 py-2">Reset</button>
                </div>
                </div>

                <div class="registration-step" data-step="account" {{ $showAccountStep ? '' : 'hidden' }}>
                <hr class="my-4">
                <p class="fw-semibold text-muted small text-uppercase mb-3">Account Credentials</p>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" id="username" name="username"
                               value="{{ old('username') }}"
                               class="form-control @error('username') is-invalid @enderror"
                               placeholder="Choose a username">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 characters">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repeat password">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 pt-2">
                    <button type="button" id="previous-step" class="btn btn-outline-secondary px-5 py-2">Back</button>
                    <button type="submit" class="btn btn-success px-5 py-2">Submit Registration</button>
                </div>
                </div>

                <p class="text-center text-muted small mt-3 mb-0">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in here</a>
                </p>
            </form>
        </div>
    </div>
</section>

<script>
    const registrationForm = document.getElementById('resident-registration-form');
    const detailsStep = registrationForm.querySelector('[data-step="details"]');
    const accountStep = registrationForm.querySelector('[data-step="account"]');
    const nextStepButton = document.getElementById('next-step');
    const previousStepButton = document.getElementById('previous-step');
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const ageInput = document.getElementById('age');

    function updateAge() {
        const dob = new Date(dateOfBirthInput.value);

        if (isNaN(dob)) {
            ageInput.value = '';
            return;
        }

        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        ageInput.value = age;
    }

    dateOfBirthInput.addEventListener('change', updateAge);

    function showStep(step) {
        const showAccount = step === 'account';

        detailsStep.hidden = showAccount;
        accountStep.hidden = !showAccount;

        const focusTarget = showAccount
            ? document.getElementById('username')
            : document.getElementById('last_name');

        if (focusTarget) {
            focusTarget.focus();
        }
    }

    nextStepButton.addEventListener('click', function () {
        showStep('account');
    });

    previousStepButton.addEventListener('click', function () {
        showStep('details');
    });

    registrationForm.addEventListener('submit', function (event) {
        if (!detailsStep.hidden) {
            event.preventDefault();
            event.stopImmediatePropagation();
            showStep('account');
        }
    }, true);

    // Password show/hide toggle (works for both password fields)
    document.querySelectorAll('.toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon  = this.querySelector('i');
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            icon.classList.toggle('fa-eye',      !show);
            icon.classList.toggle('fa-eye-slash', show);
        });
    });

    (function keepRegistrationDraft() {
        const storageKey = registrationForm.dataset.draftKey;
        const excludedNames = new Set(['_token', 'password', 'password_confirmation', 'health_status']);
        const draftFields = Array.from(registrationForm.elements).filter(function (field) {
            return field.name && !excludedNames.has(field.name) && !field.disabled;
        });

        function readDraft() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '{}');
            } catch (error) {
                return {};
            }
        }

        function writeDraft() {
            const draft = {};

            draftFields.forEach(function (field) {
                draft[field.name] = field.type === 'checkbox' ? field.checked : field.value;
            });

            localStorage.setItem(storageKey, JSON.stringify(draft));
        }

        function restoreDraft() {
            const draft = readDraft();

            draftFields.forEach(function (field) {
                if (!Object.prototype.hasOwnProperty.call(draft, field.name)) {
                    return;
                }

                if (field.type === 'checkbox') {
                    field.checked = Boolean(draft[field.name]);
                    return;
                }

                if (!field.value) {
                    field.value = draft[field.name];
                }
            });

            updateAge();
        }

        restoreDraft();

        draftFields.forEach(function (field) {
            field.addEventListener('input', writeDraft);
            field.addEventListener('change', writeDraft);
        });

        registrationForm.addEventListener('submit', function () {
            localStorage.removeItem(storageKey);
        });

        registrationForm.addEventListener('reset', function () {
            localStorage.removeItem(storageKey);
            setTimeout(function () {
                updateAge();
                showStep('details');
            }, 0);
        });
    })();
</script>
@endsection
