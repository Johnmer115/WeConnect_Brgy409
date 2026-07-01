@extends('admin.layouts.layout')

@section('title', 'Accounts - WeConnect Brgy 409')
@section('page-title', 'Accounts')
@section('flash-wrapper-class', 'd-none')

@section('content')
<div class="container-fluid admin-page cert-page">
    <div class="cert-header">
        <div>
            <h1 class="cert-title">Accounts Management</h1>
            <p class="cert-subtitle">Manage administrative and resident accounts and credentials</p>
        </div>
        <a href="{{ route('admin.accounts.create') }}" class="cert-btn-add" title="Register Account" aria-label="Register Account">
            <i class="fas fa-user-plus fa-fw"></i>
            Register Account
        </a>
    </div>

    @if(session('success'))
        <div class="cert-alert cert-alert-success" id="cert-flash-success">
            <i class="fas fa-check-circle fa-fw"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="cert-filters-bar">
        <form method="GET" action="{{ route('admin.accounts.index') }}" class="cert-filters-form" id="accounts-filter-form">
            <div class="cert-filter-group cert-filter-group--search">
                <label class="cert-filter-label" for="accounts-search">Search</label>
                <div class="input-group cert-search-group">
                    <span class="input-group-text">
                        <i class="fas fa-search fa-fw"></i>
                    </span>
                    <input type="search"
                           id="accounts-search"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control cert-search-input"
                           placeholder="Search by name, username, or position">
                    <button type="submit" class="cert-search-btn" title="Search" aria-label="Search">
                        Search
                    </button>
                </div>
            </div>
        </form>
        <div class="cert-total-badge">
            {{ $accounts->total() }} account{{ $accounts->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    <div class="cert-table-card">
        <div class="table-responsive">
            <table class="cert-table" id="accounts-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">ID Code</th>
                        <th style="width: 25%;">Account Name</th>
                        <th style="width: 15%;">Username</th>
                        <th style="width: 15%;">Position</th>
                        <th style="width: 12%;">Account Type</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 11%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr id="account-row-{{ $account->id }}">
                            <td>
                                <span class="fw-semibold text-muted small">ACC-{{ str_pad($account->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="cert-td-name">
                                {{ $account->name }}
                                <div class="entity-subtitle" style="font-size: 0.78rem; color: #64748b; font-weight: normal;">{{ $account->email ?? 'No email' }}</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-muted small">{{ $account->username }}</span>
                            </td>
                            <td>
                                <span class="text-muted small">{{ $account->position_label }}</span>
                            </td>
                            <td>
                                <span class="account-type-badge account-type-badge--{{ $account->account_type === 'Admin' ? 'admin' : 'user' }}">
                                    {{ $account->account_type }}
                                </span>
                            </td>
                            <td>
                                <span class="account-status-badge account-status-badge--{{ $account->status }}">
                                    <i class="fas {{ $account->status === 'active' ? 'fa-check-circle' : ($account->status === 'inactive' ? 'fa-circle-xmark' : 'fa-clock') }} fa-fw"></i>
                                    {{ $account->status }}
                                </span>
                            </td>
                            <td>
                                <div class="cert-action-group justify-content-center">
                                    <a href="{{ route('admin.accounts.edit', $account) }}"
                                       class="cert-action-btn cert-action-edit"
                                       title="Edit Account"
                                       aria-label="Edit Account">
                                        <i class="fas fa-pen-to-square fa-fw"></i>
                                    </a>
                                    <button type="button"
                                            class="cert-action-btn cert-action-delete btn-delete-account"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteAccountModal"
                                            data-name="{{ $account->name }}"
                                            data-action-url="{{ route('admin.accounts.destroy', $account) }}"
                                            title="Delete Account"
                                            aria-label="Delete Account">
                                        <i class="fas fa-trash-can fa-fw"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="cert-empty">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No accounts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="cert-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Showing {{ $accounts->firstItem() ?? 0 }} to {{ $accounts->lastItem() ?? 0 }} of {{ $accounts->total() }} accounts
            </div>
            @if($accounts->hasPages())
                <div>
                    {{ $accounts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── DELETE ACCOUNT CONFIRMATION MODAL ────────────────────────── --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-confirm-delete modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteAccountModalLabel">
                    <i class="fas fa-triangle-exclamation fa-lg"></i>
                    <span>Delete Account</span>
                </h5>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
                    Are you sure you want to delete the login account for <strong id="delete-account-name"></strong>?
                </p>
                <hr class="my-3" style="border-color: #e2e8f0; opacity: 0.8;">
                <p class="mb-0 text-danger small" style="font-style: italic; font-weight: 500;">
                    <i class="fas fa-triangle-exclamation fa-fw me-1"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer border-0 pb-4 pt-2 px-4">
                <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal" style="border-radius: 8px; color: #475569;">Cancel</button>
                <form id="delete-account-form" method="POST" action="" class="d-inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold px-4" style="border-radius: 8px; background: #dc2626; border-color: #dc2626;">Yes, Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        'use strict';
        const deleteForm = document.getElementById('delete-account-form');
        const deleteName = document.getElementById('delete-account-name');

        document.querySelectorAll('.btn-delete-account').forEach(button => {
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
