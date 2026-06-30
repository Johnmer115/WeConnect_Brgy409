@extends('admin.layouts.layout')

@section('title', 'Residents - WeConnect Brgy 409')
@section('page-title', 'Residents')

@section('content')
<div class="container-fluid admin-page">
    <div class="resident-master-panel">
        <div class="table-panel resident-table-panel">
            <div class="table-panel-toolbar">
                <h1 class="table-panel-title">
                    <i class="fas fa-users fa-fw text-primary"></i>
                    Resident Records
                </h1>

                <form method="GET" action="{{ route('admin.residents.index') }}"
                    class="d-flex align-items-center gap-1 ms-auto">

                    <div class="input-group" style="width:200px;">
                        <span class="input-group-text bg-white border-end-0 px-2">
                            <i class="fas fa-search text-muted" style="font-size:.75rem;"></i>
                        </span>
                        <input type="search" name="search"
                            value="{{ $search ?? '' }}"
                            class="form-control border-start-0 ps-0"
                            placeholder="Search resident"
                            style="font-size:.82rem;">
                    </div>

                    <a href="{{ route('admin.residents.index') }}"
                    class="btn btn-outline-secondary table-icon-btn"
                    title="Reset" aria-label="Reset search">
                        <i class="fas fa-rotate-left fa-fw"></i>
                    </a>

                    <a href="{{ route('admin.residents.create') }}"
                    class="btn btn-primary table-icon-btn"
                    title="Register resident" aria-label="Register resident">
                        <i class="fas fa-user-plus fa-fw"></i>
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle admin-table resident-master-table mb-0">
                    <thead>
                        <tr>
                            <th>Given Name</th>
                            <th class="text-center">Purok</th>
                            <th>Home Address</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residents as $resident)
                            <tr>
                                <td class="resident-name-cell">{{ $resident->full_name }}</td>
                                <td class="text-center">
                                    @if($resident->purok)
                                        <span class="d-inline-flex align-items-center gap-2">
                                            <span class="purok-swatch-dot" style="width: 10px; height: 10px; border-radius: 50%; display: inline-block; background-color: {{ $resident->purok->color_code }}; border: 1px solid rgba(0,0,0,0.15);" title="{{ $resident->purok->name }}"></span>
                                            <span class="small text-muted">{{ $resident->purok->name }}</span>
                                        </span>
                                    @else
                                        <span class="text-muted small">Unassigned</span>
                                    @endif
                                </td>
                                <td class="resident-address-cell">{{ $resident->full_address }}</td>
                                <td>
                                    <span class="resident-status {{ $resident->verified_at ? 'resident-status--alive' : 'resident-status--pending' }}">
                                        <i class="fas {{ $resident->verified_at ? 'fa-gem' : 'fa-clock' }} fa-fw"></i>
                                        {{ $resident->verified_at ? $resident->health_status : 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        @unless ($resident->verified_at)
                                            <form method="POST" action="{{ route('admin.residents.verify', $resident) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary table-icon-btn" title="Accept registration" aria-label="Accept registration">
                                                    <i class="fas fa-check fa-fw"></i>
                                                </button>
                                            </form>
                                        @endunless
                                        <a href="{{ route('admin.residents.show', $resident) }}" class="btn btn-sm btn-outline-primary table-icon-btn" title="View resident" aria-label="View resident">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                        <a href="{{ route('admin.residents.edit', $resident) }}" class="btn btn-sm btn-outline-primary table-icon-btn" title="Edit resident" aria-label="Edit resident">
                                            <i class="fas fa-pen fa-fw"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.residents.destroy', $resident) }}" class="m-0" onsubmit="return confirm('Delete this resident record? This will also remove the linked login account if there is one.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger table-icon-btn" title="Delete resident" aria-label="Delete resident">
                                                <i class="fas fa-trash fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
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
