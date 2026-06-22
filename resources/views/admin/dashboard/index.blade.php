@extends('admin.layouts.layout')

@section('title', 'Dashboard - WeConnect Brgy 409')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid admin-page">
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-2 bg-primary-subtle text-primary"><i class="fas fa-users fa-fw"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($residentCount) }}</div>
                        <div class="small text-muted">Registered residents</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-2 bg-warning-subtle text-warning"><i class="fas fa-user-clock fa-fw"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($pendingResidentCount) }}</div>
                        <div class="small text-muted">Pending verification</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="stat-icon rounded-2 bg-success-subtle text-success"><i class="fas fa-user-shield fa-fw"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($adminCount) }}</div>
                        <div class="small text-muted">Admin accounts</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <div class="table-panel">
                <div class="table-panel-toolbar">
                    <div>
                        <h2 class="table-panel-title">
                            <i class="fas fa-users fa-fw text-primary"></i>
                            Recent Residents
                        </h2>
                        <p class="fs-7 text-muted mb-0">Latest records from resident registration.</p>
                    </div>
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-users fa-fw me-1"></i> View all
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Purok</th>
                                <th>Status</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentResidents as $resident)
                                <tr>
                                    <td>
                                        <div class="table-entity">
                                            <div class="entity-avatar">{{ strtoupper(substr($resident->first_name, 0, 1) . substr($resident->last_name, 0, 1)) }}</div>
                                            <div>
                                                <div class="entity-title">{{ $resident->full_name }}</div>
                                                <div class="entity-subtitle">{{ $resident->gender }} &middot; {{ $resident->age }} years old</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="table-muted-text">{{ $resident->purok->name ?? 'Unassigned' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $resident->verified_at ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ $resident->verified_at ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $resident->created_at?->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-table-state">
                                            <i class="fas fa-folder-open"></i>
                                            No resident records yet.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <p class="small text-uppercase text-muted fw-semibold mb-2">Barangay Details</p>
                    <h2 class="h5 mb-2">Barangay 409, Manila City</h2>
                    <p class="text-muted fs-7 mb-3">Central admin panel for residents, certificate requests, reports, logs, and account access.</p>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users fa-fw me-1"></i> Manage Residents
                        </a>
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user-shield fa-fw me-1"></i> Admin Accounts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
