@extends('admin.layouts.layout')

@section('title', 'Admin Accounts - WeConnect Brgy 409')
@section('page-title', 'Admin Accounts')

@section('content')
<div class="container-fluid admin-page">
    <div class="card border-0 shadow-sm rounded-3 p-3">
        <div class="table-panel">
            <div class="table-panel-toolbar">
                <h1 class="table-panel-title">
                    <i class="fas fa-user-shield fa-fw text-primary"></i>
                    Admin Accounts List
                </h1>

                <form method="GET" action="{{ route('admin.accounts.index') }}" class="table-panel-actions">
                    <div class="input-group input-group-sm table-search">
                        <span class="input-group-text bg-white"><i class="fas fa-search fa-fw text-muted"></i></span>
                        <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by name, username, or role">
                    </div>
                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-rotate-left fa-fw me-1"></i> Reset
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle admin-table mb-0">
                    <thead>
                        <tr>
                            <th>ID Code</th>
                            <th>Account Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td><span class="table-code">ADM-{{ str_pad($account->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="table-primary-text">{{ $account->name }}</div>
                                    <div class="entity-subtitle">{{ $account->email ?? 'No email' }}</div>
                                </td>
                                <td><span class="table-code">{{ $account->username }}</span></td>
                                <td><span class="table-muted-text">{{ ucfirst($account->role) }}</span></td>
                                <td><span class="table-muted-text">{{ $account->created_at?->format('m/d/Y') }}</span></td>
                                <td>
                                    <span class="badge rounded-pill {{ $account->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-table-state">
                                        <i class="fas fa-user-shield"></i>
                                        No admin accounts found.
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
