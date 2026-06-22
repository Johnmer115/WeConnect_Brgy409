@extends('loginpage.layout')

@section('content')
<section class="login-shell d-flex align-items-center justify-content-center py-4 px-3">
    <div class="card login-card">
        <div class="card-body p-4 p-sm-5">

            <div class="text-center mb-4">
                <div class="brand-mark mb-3" aria-hidden="true">
                    <i class="fas fa-landmark"></i>
                </div>
                <p class="text-uppercase small fw-semibold text-primary mb-1">Barangay 409 - Zone 42</p>
                <h1 class="h4 fw-bold mb-1">WeConnect</h1>
                <p class="small text-muted mb-0">Sign in to your barangay account</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success py-2 px-3 small" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="login-form">
                @csrf

                <div class="mb-3">
                    <label for="account" class="form-label">Username</label>
                    <input
                        type="text"
                        id="account"
                        name="account"
                        value="{{ old('account') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your username or email"
                        class="form-control @error('account') is-invalid @enderror"
                    >
                    @error('account')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <div class="input-group">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="form-control @error('password') is-invalid @enderror"
                        >
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="toggle-password"
                            aria-label="Show password"
                        >
                            <i class="fas fa-eye" id="toggle-password-icon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                 <div class="form-check small mb-2">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                    Sign In
                </button>
            </form>

             {{-- ── DIVIDER + REGISTER LINK ── --}}
            <div class="d-flex align-items-center gap-2 my-3">
                <hr class="flex-grow-1 m-0">
                <span class="text-muted small">or</span>
                <hr class="flex-grow-1 m-0">
            </div>

            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                Register as Resident
            </a>
            
            <div class="text-center mt-4">
                <p class="text-muted small mb-0">Use your assigned username/email and password.</p>
            </div>

        </div>
    </div>
</section>

<script>
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('toggle-password');
    const togglePasswordIcon = document.getElementById('toggle-password-icon');

    togglePasswordButton.addEventListener('click', function () {
        const showPassword = passwordInput.type === 'password';

        passwordInput.type = showPassword ? 'text' : 'password';
        togglePasswordButton.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
        togglePasswordIcon.classList.toggle('fa-eye', !showPassword);
        togglePasswordIcon.classList.toggle('fa-eye-slash', showPassword);
    });
</script>
@endsection
