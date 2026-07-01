@extends('admin.layouts.layout')

@section('title', 'Activity Logs - WeConnect Brgy 409')
@section('page-title', 'Activity Logs')
@section('flash-wrapper-class', 'd-none')

@section('content')
<div class="container-fluid admin-page cert-page">

    {{-- Header Bar --}}
    <div class="cert-header">
        <div>
            <h1 class="cert-title">System Activity Logs</h1>
            <p class="cert-subtitle">Audit trail of logins, administrative registrations, edits, and deletions</p>
        </div>
    </div>

    @if(session('success'))
        <div class="cert-alert cert-alert-success" id="cert-flash-success">
            <i class="fas fa-check-circle fa-fw"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters Bar --}}
    <div class="cert-filters-bar">
        <form method="GET" action="{{ route('admin.activity.index') }}" class="cert-filters-form d-flex align-items-end flex-wrap gap-3" id="activity-filter-form">
            <div class="cert-filter-group cert-filter-group--search mb-0">
                <label class="cert-filter-label" for="activity-search">Search Logs</label>
                <div class="input-group cert-search-group">
                    <span class="input-group-text">
                        <i class="fas fa-search fa-fw"></i>
                    </span>
                    <input type="search"
                           id="activity-search"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control cert-search-input"
                           placeholder="Search by user, description, or IP...">
                    <button type="submit" class="cert-search-btn" title="Search">
                        Search
                    </button>
                </div>
            </div>

            <div class="cert-filter-group mb-0">
                <label class="cert-filter-label" for="filter-module">Module</label>
                <select id="filter-module" name="module" class="cert-select form-select" onchange="this.form.submit()">
                    <option value="" @selected($selectedModule === '')>All Modules</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" @selected($selectedModule === $mod)>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>

            <div class="cert-filter-group mb-0">
                <label class="cert-filter-label" for="filter-action">Action</label>
                <select id="filter-action" name="action" class="cert-select form-select" onchange="this.form.submit()">
                    <option value="" @selected($selectedAction === '')>All Actions</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" @selected($selectedAction === $act)>{{ ucfirst($act) }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="cert-total-badge">
            {{ $logs->total() }} entry{{ $logs->total() !== 1 ? 'ies' : '' }}
        </div>
    </div>

    {{-- Activity Logs Table --}}
    <div class="cert-table-card mt-3">
        <div class="table-responsive">
            <table class="cert-table align-middle" id="activity-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Timestamp</th>
                        <th style="width: 15%;">User</th>
                        <th style="width: 12%;">Module</th>
                        <th style="width: 10%;">Action</th>
                        <th style="width: 35%;">Description</th>
                        <th style="width: 13%;">IP / Device</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>
                                <span class="fw-semibold text-dark small">{{ $log->created_at->format('Y-m-d h:i A') }}</span>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <span class="fw-bold text-slate-700">{{ $log->user_name }}</span>
                                <div class="text-muted small" style="font-size: 0.75rem;">ID: {{ $log->user_id ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @php
                                    $modLower = strtolower($log->module);
                                    $modClass = match($modLower) {
                                        'auth' => 'secondary',
                                        'accounts' => 'primary',
                                        'residents' => 'success',
                                        'announcements' => 'warning',
                                        'certificates' => 'info',
                                        default => 'dark'
                                    };
                                    $modBg = match($modLower) {
                                        'auth' => '#f1f5f9; color: #475569; border: 1px solid #cbd5e1;',
                                        'accounts' => '#eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;',
                                        'residents' => '#ecfdf5; color: #047857; border: 1px solid #a7f3d0;',
                                        'announcements' => '#fffbeb; color: #b45309; border: 1px solid #fde68a;',
                                        'certificates' => '#faf5ff; color: #6b21a8; border: 1px solid #e9d5ff;',
                                        default => '#f8fafc; color: #0f172a; border: 1px solid #e2e8f0;'
                                    };
                                @endphp
                                <span class="badge" style="padding: 0.35rem 0.65rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; {!! $modBg !!}">
                                    {{ $log->module }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $actLower = strtolower($log->action);
                                    $actBg = match($actLower) {
                                        'login' => '#dcfce7; color: #15803d;',
                                        'logout' => '#f1f5f9; color: #475569;',
                                        'create' => '#e0f2fe; color: #0369a1;',
                                        'update' => '#fef3c7; color: #b45309;',
                                        'delete' => '#fee2e2; color: #b91c1c;',
                                        default => '#f1f5f9; color: #334155;'
                                    };
                                @endphp
                                <span class="badge text-uppercase" style="padding: 0.3rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 700; {!! $actBg !!}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="text-wrap">
                                <span class="text-secondary small d-block" style="line-height: 1.4; word-break: break-word;">{{ $log->description }}</span>
                            </td>
                            <td>
                                <span class="text-muted small d-block font-monospace" style="font-size: 0.75rem;"><i class="fas fa-network-wired me-1"></i> {{ $log->ip_address ?? 'Unknown' }}</span>
                                <div class="text-muted text-truncate" style="font-size: 0.68rem; max-width: 130px;" title="{{ $log->user_agent }}">{{ $log->user_agent }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="cert-empty text-center py-4">
                                <i class="fas fa-receipt fa-2x mb-2 text-muted"></i>
                                <span class="d-block text-muted small">No activity logs recorded.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="cert-pagination d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <div class="small text-muted">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
            </div>
            @if($logs->hasPages())
                <div>
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        'use strict';
        // ── Auto-dismiss flash ───────────────────────────────────────
        var flash = document.getElementById('cert-flash-success');
        if (flash) {
            setTimeout(function () {
                flash.style.opacity = '0';
                setTimeout(function () {
                    flash.style.display = 'none';
                }, 500);
            }, 3500);
        }
    })();
</script>
@endpush
@endsection
