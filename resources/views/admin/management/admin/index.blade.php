@extends('admin.layouts.layout')

@section('title', 'Accounts - WeConnect Brgy 409')
@section('page-title', 'Accounts')

@section('content')
<div class="container-fluid admin-page">
    <div class="resident-master-panel">
        <div class="table-panel resident-table-panel">
            <div class="table-panel-toolbar">
                <h1 class="table-panel-title">
                    <i class="fas fa-user-shield fa-fw text-primary"></i>
                    Accounts
                </h1>

                <form method="GET" action="{{ route('admin.accounts.index') }}" class="table-panel-actions">
                    <div class="input-group table-search">
                        <span class="input-group-text bg-white"><i class="fas fa-search fa-fw text-muted"></i></span>
                        <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by name, username, or position">
                    </div>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary table-action-btn" title="Reset search" aria-label="Reset search">
                        <i class="fas fa-rotate-left fa-fw"></i>
                    </a>
                    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary table-action-btn table-action-btn--primary" title="Register account" aria-label="Register account">
                        <i class="fas fa-user-plus fa-fw"></i>
                        <span>Register Account</span>
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle admin-table resident-master-table mb-0">
                    <thead>
                        <tr>
                            <th>ID Code</th>
                            <th>Account Name</th>
                            <th>Username</th>
                            <th>Position</th>
                            <th>Account Type</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td class="table-code">ACC-{{ str_pad($account->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="table-primary-text">{{ $account->name }}</div>
                                    <div class="entity-subtitle">{{ $account->email ?? 'No email' }}</div>
                                </td>
                                <td><span class="table-code">{{ $account->username }}</span></td>
                                <td><span class="table-muted-text">{{ $account->position_label }}</span></td>
                                <td>
                                    <span class="account-type-badge account-type-badge--{{ $account->account_type === 'Admin' ? 'admin' : 'user' }}">
                                        {{ $account->account_type }}
                                    </span>
                                </td>
                                <td><span class="table-muted-text">{{ $account->created_at?->format('M d, Y') }}</span></td>
                                <td>
                                    <span class="badge rounded-pill {{ $account->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-sm btn-outline-primary table-icon-btn" title="Edit account" aria-label="Edit account">
                                            <i class="fas fa-pen fa-fw"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" class="m-0" onsubmit="return confirm('Delete this account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger table-icon-btn" title="Delete account" aria-label="Delete account">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-table-state">
                                        <i class="fas fa-user-shield"></i>
                                        No accounts found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="fs-7 text-muted">
                    Showing {{ $accounts->firstItem() ?? 0 }}-{{ $accounts->lastItem() ?? 0 }} of {{ $accounts->total() }} entries
                </div>
                <div>{{ $accounts->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
