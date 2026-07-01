@csrf

<div class="form-section">
    <h2 class="form-section-title">Account Details</h2>
    <div class="row g-3">
        @if ($account->exists)
            <div class="col-12 col-md-4">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" value="{{ $account->name }}" readonly>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="{{ $account->username }}" readonly>
            </div>
        @else
            <div class="col-12 col-md-4">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $account->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Juan Dela Cruz">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-4">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', $account->username) }}" class="form-control @error('username') is-invalid @enderror" placeholder="e.g. juandelacruz">
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endif
        <div class="col-12 col-md-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $account->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="e.g. name@example.com">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @unless ($account->exists)
        <div class="col-12 col-md-4">
            <label for="role" class="form-label">Position <span class="text-danger">*</span></label>
            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
                <option value="">Select position</option>
                @foreach ($roleOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $account->role) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="form-text small">Resident accounts are shown as User accounts.</div>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        @endunless
        <div class="col-12 col-md-4">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $account->status ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="password" class="form-label">{{ $account->exists ? 'New Password' : 'Password' }} <span class="text-danger">{{ $account->exists ? '' : '*' }}</span></label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ $account->exists ? 'Leave blank to keep current password' : 'Min. 8 characters' }}">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12 col-md-4">
            <label for="password_confirmation" class="form-label">{{ $account->exists ? 'Confirm New Password' : 'Confirm Password' }}</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat password">
        </div>
    </div>
</div>
