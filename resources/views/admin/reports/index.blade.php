@extends('admin.layouts.layout')

@section('title', 'Reports - WeConnect Brgy 409')
@section('page-title', 'Reports')

@section('content')
<div class="container-fluid admin-page cert-page">
    <div class="cert-header">
        <div>
            <h1 class="cert-title">Barangay Reports</h1>
            <p class="cert-subtitle">Overview and statistics of categorized resident groups</p>
        </div>
    </div>

    <div class="cert-filters-bar align-items-center">
        <div class="report-filter-bar d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ route('admin.reports.index') }}"
               class="report-filter {{ $selectedKinds === [] ? 'report-filter--all active' : 'report-filter--all' }}">
                View All
            </a>

            @foreach ($kinds as $key => $label)
                @php
                    $nextKinds = $selectedKinds;

                    if (in_array($key, $nextKinds, true)) {
                        $nextKinds = array_values(array_diff($nextKinds, [$key]));
                    } else {
                        $nextKinds[] = $key;
                    }

                    $filterUrl = $nextKinds === []
                        ? route('admin.reports.index')
                        : route('admin.reports.index') . '?' . http_build_query(['kind' => $nextKinds]);
                @endphp

                <a href="{{ $filterUrl }}"
                   class="report-filter report-filter--{{ $key }} {{ in_array($key, $selectedKinds, true) ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="cert-total-badge ms-md-auto mt-2 mt-md-0">
            {{ $residents->count() }} record{{ $residents->count() !== 1 ? 's' : '' }}
        </div>
    </div>

    <div class="cert-table-card">
        <div class="table-responsive">
            <table class="cert-table" id="reports-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Name of the Residents</th>
                        <th style="width: 40%;">Full Address</th>
                        <th style="width: 30%;">Kind of Resident</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($residents as $resident)
                        <tr id="resident-row-{{ $resident->id }}">
                            <td class="cert-td-name">{{ $resident->full_name }}</td>
                            <td class="cert-td-address">
                                {{ $resident->home_address ?? 'No address saved' }}
                                @if ($resident->purok)
                                    <span class="text-muted">, {{ $resident->purok->name }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="report-kind-list">
                                    @foreach ($resident->report_kinds as $key => $label)
                                        <span class="report-kind report-kind--{{ $key }}">{{ $label }}</span>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="cert-empty">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No resident report records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="cert-pagination">
            <div class="small text-muted">
                Showing {{ $residents->count() }} entry{{ $residents->count() !== 1 ? 'ies' : 'y' }}
            </div>
        </div>
    </div>
</div>
@endsection
