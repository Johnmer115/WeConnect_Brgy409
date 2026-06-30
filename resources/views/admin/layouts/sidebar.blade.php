<aside class="sidebar" id="sidebar">
    <div class="sb-logo">
        <div class="sb-logo-icon">
            <img src="{{ asset('image/logo/arellano_logo.png') }}" alt="WeConnect Logo">
        </div>
        <div class="sb-logo-name">
            WeConnect | Brgy 409
        </div>
    </div>

    <nav class="sb-nav">

        {{-- ── Overview ── --}}
        <p class="sb-section">Overview</p>
        <ul class="sb-list">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home fa-fw"></i>
                    <span>Home</span>
                </a>
            </li>
        </ul>

        {{-- ── Management ── --}}
        <p class="sb-section">Management</p>
        <ul class="sb-list">
            <li>
                <a href="{{ route('admin.announcements.index') }}"
                   class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn fa-fw"></i>
                    <span>Announcements</span>
                </a>
            </li>
            <li>
                @php $residentsMenuOpen = request()->routeIs('admin.residents.*') || request()->routeIs('admin.certificates.*'); @endphp
                <div class="sb-parent {{ $residentsMenuOpen ? 'is-open' : '' }}">
                    <a href="{{ route('admin.residents.index') }}"
                       class="sb-parent-link {{ $residentsMenuOpen ? 'active' : '' }}">
                        <i class="fas fa-users fa-fw"></i>
                        <span>Residents</span>
                    </a>
                    <button type="button"
                            class="sb-subtoggle"
                            data-target="admin-residents-submenu"
                            aria-expanded="{{ $residentsMenuOpen ? 'true' : 'false' }}"
                            aria-label="Toggle residents submenu">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <ul class="sb-sublist" id="admin-residents-submenu" {{ $residentsMenuOpen ? '' : 'hidden' }}>
                    <li>
                        <a href="{{ route('admin.certificates.index') }}"
                           class="{{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                            <i class="fas fa-file-signature fa-fw"></i>
                            <span>Issuing Certificates</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                 @php $accountsMenuOpen = request()->routeIs('admin.accounts.*'); @endphp
                <a href="{{ route('admin.accounts.index') }}"
                   class="{{ $accountsMenuOpen ? 'active' : '' }}">
                    <i class="fas fa-user-shield fa-fw"></i>
                    <span>Accounts</span>
                </a>
            </li>
        </ul>

        {{-- ── Reports & Logs ── --}}
        <p class="sb-section">System Logs</p>
        <ul class="sb-list">
            <li>
                <a href="{{ route('admin.reports.index') }}"
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

    <div class="sb-footer">
        <div class="sb-footer-chip d-flex justify-content-center">
            <i class="fas fa-shield-halved fa-fw"></i>
            <span>Admin Panel</span>
        </div>
        <div class="sb-footer-note text-center">@ Copyright 2026 by WeConnect | Brgy 409</div>
    </div>
</aside>
