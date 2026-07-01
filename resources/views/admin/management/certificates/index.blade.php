@extends('admin.layouts.layout')

@section('title', 'Certificates - WeConnect Brgy 409')
@section('page-title', 'Certificates')
@section('flash-wrapper-class', 'd-none')

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


    {{-- ── Filters ─────────────────────────────────────────────────── --}}
    <div class="cert-filters-bar">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="cert-filters-form" id="cert-filter-form">
            <div class="cert-filter-group cert-filter-group--search">
                <label class="cert-filter-label" for="cert-search">Search</label>
                <div class="input-group cert-search-group">
                    <span class="input-group-text">
                        <i class="fas fa-search fa-fw"></i>
                    </span>
                    <input type="search"
                           id="cert-search"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control cert-search-input"
                           placeholder="Search name or purpose">
                    <button type="submit" class="cert-search-btn" title="Search" aria-label="Search">
                        Search
                    </button>
                </div>
            </div>
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
            <table class="cert-table cert-table-issued" id="cert-table">
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

                                    {{-- Delete --}}
                                    <button type="button"
                                            class="cert-action-btn cert-action-delete btn-delete-cert"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteCertModal"
                                            data-name="{{ $cert->full_name }}"
                                            data-type="{{ $cert->type_label }}"
                                            data-action-url="{{ route('admin.certificates.destroy', $cert) }}"
                                            title="Delete">
                                        <i class="fas fa-trash-can fa-fw"></i>
                                    </button>
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
        <div class="cert-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Showing {{ $certificates->firstItem() ?? 0 }} to {{ $certificates->lastItem() ?? 0 }} of {{ $certificates->total() }} records
            </div>
            @if($certificates->hasPages())
                <div>
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── DELETE CERTIFICATE CONFIRMATION MODAL ────────────────────── --}}
<div class="modal fade" id="deleteCertModal" tabindex="-1" aria-labelledby="deleteCertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-confirm-delete modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteCertModalLabel">
                    <i class="fas fa-triangle-exclamation fa-lg"></i>
                    <span>Delete Record</span>
                </h5>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
                    Are you sure you want to delete the <span id="delete-cert-type" class="fw-semibold"></span> record for <strong id="delete-cert-name"></strong>?
                </p>
                <hr class="my-3" style="border-color: #e2e8f0; opacity: 0.8;">
                <p class="mb-0 text-danger small" style="font-style: italic; font-weight: 500;">
                    <i class="fas fa-triangle-exclamation fa-fw me-1"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer border-0 pb-4 pt-2 px-4">
                <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal" style="border-radius: 8px; color: #475569;">Cancel</button>
                <form id="delete-cert-form" method="POST" action="" class="d-inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold px-4" style="border-radius: 8px; background: #dc2626; border-color: #dc2626;">Yes, Delete</button>
                </form>
            </div>
        </div>
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
        setTimeout(function () {
            flash.style.opacity = '0';
            setTimeout(function () {
                flash.style.display = 'none';
            }, 500);
        }, 3500);
    }

    // ── Dynamic Delete Modal ─────────────────────────────────────
    const deleteForm = document.getElementById('delete-cert-form');
    const deleteName = document.getElementById('delete-cert-name');
    const deleteType = document.getElementById('delete-cert-type');

    document.querySelectorAll('.btn-delete-cert').forEach(button => {
        button.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const type = this.getAttribute('data-type');
            const actionUrl = this.getAttribute('data-action-url');

            if (deleteForm && deleteName && deleteType) {
                deleteForm.setAttribute('action', actionUrl);
                deleteName.textContent = name;
                deleteType.textContent = type;
            }
        });
    });
})();
</script>
@endpush
