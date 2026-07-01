@extends('admin.layouts.layout')

@section('title', 'Residents - WeConnect Brgy 409')
@section('page-title', 'Residents')
@section('flash-wrapper-class', 'd-none')

@section('content')
<div class="container-fluid admin-page cert-page">
    <div class="cert-header">
        <div>
            <h1 class="cert-title">Resident Records</h1>
            <p class="cert-subtitle">Manage barangay residents and linked accounts</p>
        </div>
        <a href="{{ route('admin.residents.create') }}" class="cert-btn-add" title="Register resident" aria-label="Register resident">
            <i class="fas fa-user-plus fa-fw"></i>
            Register Resident
        </a>
    </div>

    @if(session('success'))
        <div class="cert-alert cert-alert-success" id="cert-flash-success">
            <i class="fas fa-check-circle fa-fw"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="cert-filters-bar">
        <form method="GET" action="{{ route('admin.residents.index') }}" class="cert-filters-form" id="resident-filter-form">
            <div class="cert-filter-group cert-filter-group--search">
                <label class="cert-filter-label" for="resident-search">Search</label>
                <div class="input-group cert-search-group">
                    <span class="input-group-text">
                        <i class="fas fa-search fa-fw"></i>
                    </span>
                    <input type="search"
                           id="resident-search"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control cert-search-input"
                           placeholder="Search resident">
                    <button type="submit" class="cert-search-btn" title="Search" aria-label="Search">
                        Search
                    </button>
                </div>
            </div>
        </form>
        <div class="cert-total-badge">
            {{ $residents->total() }} record{{ $residents->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    <div class="cert-table-card">
        <div class="table-responsive">
            <table class="cert-table resident-cert-table" id="resident-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Purok</th>
                        <th>Full Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($residents as $resident)
                        <tr id="resident-row-{{ $resident->id }}" data-id="{{ $resident->id }}">
                            <td class="cert-td-name">
                                {{ $resident->full_name }}
                                @if($resident->user)
                                    <div class="entity-subtitle">Username: {{ $resident->user->username }}</div>
                                @endif
                            </td>
                            <td>
                                @if($resident->purok)
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <span class="purok-swatch-dot" style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background-color: {{ $resident->purok->color_code }}; border: 1px solid rgba(0,0,0,0.15);" title="{{ $resident->purok->name }}"></span>
                                        <span class="small text-muted">{{ $resident->purok->name }}</span>
                                    </span>
                                @else
                                    <span class="text-muted small">Unassigned</span>
                                @endif
                            </td>
                            <td class="cert-td-address" title="{{ $resident->full_address }}">{{ $resident->full_address }}</td>
                            <td>
                                <span class="resident-status {{ $resident->verified_at ? 'resident-status--alive' : 'resident-status--pending' }}">
                                    <i class="fas {{ $resident->verified_at ? 'fa-gem' : 'fa-clock' }} fa-fw"></i>
                                    {{ $resident->verified_at ? $resident->health_status : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="cert-action-group">
                                    @unless ($resident->verified_at)
                                        <form method="POST" action="{{ route('admin.residents.verify', $resident) }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="cert-action-btn cert-action-edit" title="Accept registration" aria-label="Accept registration">
                                                <i class="fas fa-check fa-fw"></i>
                                            </button>
                                        </form>
                                    @endunless
                                    <a href="{{ route('admin.residents.show', $resident) }}"
                                       class="cert-action-btn cert-action-print"
                                       title="View resident"
                                       aria-label="View resident">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                    <a href="{{ route('admin.residents.edit', $resident) }}"
                                       class="cert-action-btn cert-action-edit"
                                       title="Edit resident"
                                       aria-label="Edit resident">
                                        <i class="fas fa-pen-to-square fa-fw"></i>
                                    </a>
                                    <button type="button"
                                            class="cert-action-btn cert-action-delete btn-delete-resident"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteResidentModal"
                                            data-name="{{ $resident->full_name }}"
                                            data-action-url="{{ route('admin.residents.destroy', $resident) }}"
                                            title="Delete resident"
                                            aria-label="Delete resident">
                                        <i class="fas fa-trash-can fa-fw"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="cert-empty">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No resident records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="cert-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Showing {{ $residents->firstItem() ?? 0 }} to {{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }} records
            </div>
            @if($residents->hasPages())
                <div>
                    {{ $residents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── DELETE RESIDENT CONFIRMATION MODAL ────────────────────────── --}}
<div class="modal fade" id="deleteResidentModal" tabindex="-1" aria-labelledby="deleteResidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-confirm-delete modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteResidentModalLabel">
                    <i class="fas fa-triangle-exclamation fa-lg"></i>
                    <span>Delete Resident Record</span>
                </h5>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
                    Are you sure you want to delete the resident record for <strong id="delete-resident-name"></strong>?
                </p>
                <hr class="my-3" style="border-color: #e2e8f0; opacity: 0.8;">
                <p class="mb-0 text-danger small" style="font-style: italic; font-weight: 500;">
                    <i class="fas fa-triangle-exclamation fa-fw me-1"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer border-0 pb-4 pt-2 px-4">
                <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal" style="border-radius: 8px; color: #475569;">Cancel</button>
                <form id="delete-resident-form" method="POST" action="" class="d-inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold px-4" style="border-radius: 8px; background: #dc2626; border-color: #dc2626;">Yes, Delete Resident</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        'use strict';
        const deleteForm = document.getElementById('delete-resident-form');
        const deleteName = document.getElementById('delete-resident-name');

        document.querySelectorAll('.btn-delete-resident').forEach(button => {
            button.addEventListener('click', function () {
                const name = this.getAttribute('data-name');
                const actionUrl = this.getAttribute('data-action-url');

                if (deleteForm && deleteName) {
                    deleteForm.setAttribute('action', actionUrl);
                    deleteName.textContent = name;
                }
            });
        });
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
