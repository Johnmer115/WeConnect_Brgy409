<aside class="sidebar" id="sidebar">
    <div class="sb-logo">
        <div class="sb-logo-icon">
            <img src="{{ asset('image/logo/arellano_logo.png') }}" alt="WeConnect Logo">
        </div>
        <div class="sb-logo-name">
            WeConnect <span>Resident</span>
        </div>
    </div>

    <nav class="sb-nav">
        <p class="sb-section">Resident</p>
        <ul class="sb-list">
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home fa-fw"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                @if ($resident->verified_at)
                    <a href="{{ route('resident.announcements') }}"
                       class="{{ request()->routeIs('resident.announcements') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn fa-fw"></i>
                        <span>Announcements</span>
                    </a>
                @else
                    <span class="sb-disabled">
                        <i class="fas fa-bullhorn fa-fw"></i>
                        <span>Announcements</span>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
</aside>
