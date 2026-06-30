<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WeConnect — Brgy 409')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('image/brgy_logo.png') }}">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
    {{-- Page-specific styles --}}
    @stack('styles')
</head>
<body>

    {{-- Sidebar injected by layout.blade.php --}}
    @yield('sidebar')

    <div class="main" id="mainContent">

        {{-- ── Top Bar ── --}}
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle" type="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <div class="flex-1">
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            </div>

            {{-- Extra topbar items (optional per page) --}}
            @yield('topbar-right')

            {{-- ── User dropdown (top-right) ── --}}
            <div class="tu-wrap dropdown">
                <button class="tu-trigger dropdown-toggle" type="button"
                        id="userDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <div class="tu-avatar">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    <div class="tu-text">
                        <span class="tu-name">{{ auth()->user()->username }}</span>
                        {{-- role replaces usertype --}}
                        <span class="tu-role">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </button>

                <ul class="dropdown-menu dropdown-menu-end tu-dropdown" aria-labelledby="userDropdown">
                    <li>
                        <div class="tu-dd-row">
                            <div>
                                <div class="tu-dd-name fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="tu-dd-role">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item tu-logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        {{-- ── Flash Messages ── --}}
        <div class="px-3 pt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show small py-2" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show small py-2" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- ── Page Content ── --}}
        <div class="content">
            @yield('content')
        </div>

    </div>{{-- /.main --}}

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Sidebar toggle --}}
    <script>
        const sidebar   = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
        const mainContent = document.getElementById('mainContent');

        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar--collapsed');
            mainContent.classList.toggle('main--expanded');
        });

        document.querySelectorAll('.sb-subtoggle').forEach(function (button) {
            button.addEventListener('click', function () {
                const submenu = document.getElementById(button.dataset.target);

                if (!submenu) {
                    return;
                }

                const expanded = button.getAttribute('aria-expanded') === 'true';
                button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                submenu.hidden = expanded;
            });
        });
    </script>

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
