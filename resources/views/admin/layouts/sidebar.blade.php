<aside class="sidebar" id="sidebar">

    {{-- ── Logo / Brand ── --}}
    <div class="sb-logo">
        <div class="sb-logo-icon">
            <img src="{{ asset('image/logo/arellano_logo.png') }}" alt="WeConnect Logo">
        </div>
        <div class="sb-logo-name">
            WeConnect <span>Brgy 409</span>
        </div>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="sb-nav">

        {{-- Main --}}
        <p class="sb-section">Main</p>
        <ul class="sb-list">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home fa-fw"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- Request --}}
        <p class="sb-section">Request</p>
        <ul class="sb-list">
            <li>
                <a href="#"
                   class="{{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt fa-fw"></i>
                    <span>Certificate</span>
                </a>
            </li>
        </ul>

        {{-- Management --}}
        <p class="sb-section">Management</p>
        <ul class="sb-list">
            <li>
                <a href="{{ route('admin.residents.index') }}"
                   class="{{ request()->routeIs('admin.residents.*') ? 'active' : '' }}">
                    <i class="fas fa-users fa-fw"></i>
                    <span>Residents</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.accounts.index') }}"
                   class="{{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield fa-fw"></i>
                    <span>Admin Accounts</span>
                </a>
            </li>
        </ul>

        {{-- Reports --}}
        <p class="sb-section">Reports</p>
        <ul class="sb-list">
            <li>
                <a href="#"
                   class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar fa-fw"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="{{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history fa-fw"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
        </ul>

    </nav>
</aside>
