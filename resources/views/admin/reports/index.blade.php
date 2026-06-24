@extends('admin.layouts.layout')

@section('title', 'Reports - WeConnect Brgy 409')
@section('page-title', 'Reports')

@section('content')
<div class="container-fluid admin-page">
    <div class="resident-master-panel">
        <div class="table-panel resident-table-panel">
            <div class="table-panel-toolbar">
                <h1 class="table-panel-title">
                    <i class="fas fa-chart-bar fa-fw text-primary"></i>
                    Barangay Report
                </h1>

                <div class="report-filter-bar ms-auto">
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
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle admin-table resident-master-table mb-0">
                    <thead>
                        <tr>
                            <th>Name of the Residents</th>
                            <th>Full Address</th>
                            <th>Kind of Resident</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($residents as $resident)
                            <tr>
                                <td class="resident-name-cell">{{ $resident->full_name }}</td>
                                <td class="resident-address-cell">
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
                                <td colspan="3">
                                    <div class="empty-table-state">
                                        <i class="fas fa-folder-open"></i>
                                        No resident report records found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="fs-7 text-muted">
                    Showing {{ $residents->count() }} report entries
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
