@extends('admin.layouts.layout')

@section('title', 'Residents - WeConnect Brgy 409')
@section('page-title', 'Residents')

@section('content')
<div class="container-fluid admin-page">
    @isset($selectedResident)
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <p class="small text-uppercase text-muted fw-semibold mb-1">Resident Profile</p>
                        <h1 class="h5 mb-1">{{ $selectedResident->full_name }}</h1>
                        <p class="text-muted small mb-0">
                            {{ $selectedResident->home_address ?? 'No address saved' }}
                            @if ($selectedResident->purok)
                                &middot; {{ $selectedResident->purok->name }}
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-xmark fa-fw me-1"></i> Close
                    </a>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Gender</div>
                        <div class="fw-semibold">{{ $selectedResident->gender }}</div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Age</div>
                        <div class="fw-semibold">{{ $selectedResident->age }}</div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Mobile</div>
                        <div class="fw-semibold">{{ $selectedResident->mobile_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Account</div>
                        <div class="fw-semibold">{{ $selectedResident->user?->username ?? 'No login account' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <div class="card border-0 shadow-sm rounded-3 p-3">
        <div class="table-panel">
            <div class="table-panel-toolbar">
                <h1 class="table-panel-title">
                    <i class="fas fa-users fa-fw text-primary"></i>
                    Residents List
                </h1>

                <form method="GET" action="{{ route('admin.residents.index') }}" class="table-panel-actions">
                    <div class="input-group input-group-sm table-search">
                        <span class="input-group-text bg-white"><i class="fas fa-search fa-fw text-muted"></i></span>
                        <input type="search" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by name, contact, or email">
                    </div>
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-rotate-left fa-fw me-1"></i> Reset
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle admin-table mb-0">
                    <thead>
                        <tr>
                            <th>ID Code</th>
                            <th>Resident Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residents as $resident)
                            <tr>
                                <td><span class="table-code">RES-{{ str_pad($resident->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                <td>
                                    <div class="table-primary-text">{{ $resident->full_name }}</div>
                                    <div class="entity-subtitle">{{ $resident->gender }} &middot; {{ $resident->age }} years old</div>
                                </td>
                                <td>
                                    <div class="table-muted-text">{{ $resident->home_address ?? 'No address saved' }}</div>
                                    <div class="entity-subtitle">{{ $resident->purok->name ?? 'Unassigned' }}</div>
                                </td>
                                <td>
                                    <div class="table-primary-text">{{ $resident->mobile_number ?? 'No mobile' }}</div>
                                    <div class="entity-subtitle">{{ $resident->email ?? 'No email' }}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $resident->verified_at ? 'text-bg-success' : 'text-bg-warning' }}">
                                        {{ $resident->verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        <a href="{{ route('admin.residents.show', $resident) }}" class="btn btn-sm btn-outline-primary table-icon-btn" title="View resident">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-table-state">
                                        <i class="fas fa-folder-open"></i>
                                        No resident records found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="fs-7 text-muted">
                    Showing {{ $residents->firstItem() ?? 0 }}-{{ $residents->lastItem() ?? 0 }} of {{ $residents->total() }} entries
                </div>
                <div>{{ $residents->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
