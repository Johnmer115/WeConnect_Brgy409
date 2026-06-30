@extends('admin.layouts.layout')

@section('title', 'Certificates - WeConnect Brgy 409')
@section('page-title', 'Certificates')

@section('content')
<div class="container-fluid admin-page cert-page">

    {{-- ── Header Bar ─────────────────────────────────────────────── --}}
    <div class="cert-header">
        <div>
            <h1 class="cert-title">Issuing Certificates</h1>
            <p class="cert-subtitle">Manage and track barangay certificate requests</p>
        </div>
        <a href="{{ route('admin.certificates.create') }}" class="cert-btn-add" id="btn-add-issue">
            <i class="fas fa-plus fa-fw"></i>
            Add Issue
        </a>
    </div>

    {{-- ── Flash Messages ──────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="cert-alert cert-alert-success" id="cert-flash-success">
            <i class="fas fa-check-circle fa-fw"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Filters ─────────────────────────────────────────────────── --}}
    <div class="cert-filters-bar">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="cert-filters-form" id="cert-filter-form">
            <div class="cert-filter-group">
                <label class="cert-filter-label" for="filter-type">Certificate Type</label>
                <select id="filter-type" name="type" class="cert-select" onchange="this.form.submit()">
                    <option value="" @selected($selectedType === '')>All Types</option>
                    @foreach($types as $val => $label)
                        <option value="{{ $val }}" @selected($selectedType === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cert-filter-group">
                <label class="cert-filter-label" for="filter-status">Status</label>
                <select id="filter-status" name="status" class="cert-select" onchange="this.form.submit()">
                    <option value="" @selected($selectedStatus === '')>Pending &amp; Ongoing</option>
                    @foreach($statuses as $val => $label)
                        <option value="{{ $val }}" @selected($selectedStatus === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="cert-total-badge">
            {{ $certificates->total() }} record{{ $certificates->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    {{-- ── Table ───────────────────────────────────────────────────── --}}
    <div class="cert-table-card">
        <div class="table-responsive">
            <table class="cert-table" id="cert-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Certificate Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                        <tr id="cert-row-{{ $cert->id }}" data-id="{{ $cert->id }}">
                            <td class="cert-td-name">{{ $cert->full_name }}</td>
                            <td class="cert-td-address" title="{{ $cert->full_address }}">{{ $cert->full_address }}</td>
                            <td>
                                <span class="cert-type-badge cert-type-{{ $cert->certificate_type }}">
                                    {{ $cert->type_label }}
                                </span>
                            </td>
                            <td>
                                <span class="cert-status-badge cert-status-{{ $cert->status }}" id="cert-status-{{ $cert->id }}">
                                    @if($cert->status === 'pending')
                                        <i class="fas fa-clock fa-fw"></i>
                                    @elseif($cert->status === 'ongoing')
                                        <i class="fas fa-spinner fa-fw"></i>
                                    @else
                                        <i class="fas fa-check-circle fa-fw"></i>
                                    @endif
                                    {{ $cert->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="cert-action-group">
                                    {{-- Update Status --}}
                                    <div class="cert-status-select-wrap">
                                        <select class="cert-status-select"
                                                data-id="{{ $cert->id }}"
                                                data-url="{{ route('admin.certificates.updateStatus', $cert) }}"
                                                data-token="{{ csrf_token() }}"
                                                title="Update Status">
                                            @foreach($statuses as $val => $label)
                                                <option value="{{ $val }}" @selected($cert->status === $val)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.certificates.edit', $cert) }}"
                                       class="cert-action-btn cert-action-edit"
                                       title="Edit">
                                        <i class="fas fa-pen-to-square fa-fw"></i>
                                    </a>

                                    {{-- Print --}}
                                    <a href="{{ route('admin.certificates.print', $cert) }}"
                                       class="cert-action-btn cert-action-print"
                                       target="_blank"
                                       title="Print">
                                        <i class="fas fa-print fa-fw"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="cert-empty">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No certificates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($certificates->hasPages())
            <div class="cert-pagination">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Toast Notification --}}
<div class="cert-toast" id="cert-toast" role="alert" aria-live="assertive"></div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ── AJAX Status Update ───────────────────────────────────────
    document.querySelectorAll('.cert-status-select').forEach(function (select) {
        select.addEventListener('change', function () {
            const id    = this.dataset.id;
            const url   = this.dataset.url;
            const token = this.dataset.token;
            const newStatus = this.value;
            const row   = document.getElementById('cert-row-' + id);
            const badge = document.getElementById('cert-status-' + id);

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus }),
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    // Update badge class + text
                    badge.className = 'cert-status-badge cert-status-' + data.status;
                    const icons = {
                        pending:   'fa-clock',
                        ongoing:   'fa-spinner',
                        completed: 'fa-check-circle',
                    };
                    badge.innerHTML = '<i class="fas ' + (icons[data.status] || 'fa-circle') + ' fa-fw"></i> ' + data.status_label;

                    showToast('Status updated to "' + data.status_label + '"', 'success');
                } else {
                    showToast('Failed to update status.', 'error');
                }
            })
            .catch(function () {
                showToast('Network error. Please try again.', 'error');
            });
        });
    });

    // ── Toast ────────────────────────────────────────────────────
    function showToast(msg, type) {
        const toast = document.getElementById('cert-toast');
        toast.textContent = msg;
        toast.className = 'cert-toast cert-toast-' + type + ' cert-toast-visible';
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () {
            toast.classList.remove('cert-toast-visible');
        }, 3000);
    }

    // ── Auto-dismiss flash ───────────────────────────────────────
    var flash = document.getElementById('cert-flash-success');
    if (flash) {
        setTimeout(function () { flash.style.opacity = '0'; }, 3500);
    }
})();
</script>
@endpush
