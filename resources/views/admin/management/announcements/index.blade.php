@extends('admin.layouts.layout')

@section('title', 'Announcements - WeConnect Brgy 409')
@section('page-title', 'Announcements')

@section('content')
<div class="container-fluid admin-page">
    <div class="resident-master-panel">
        <div class="table-panel resident-table-panel">
            <div class="table-panel-toolbar">
                <h1 class="table-panel-title">
                    <i class="fas fa-bullhorn fa-fw text-primary"></i>
                    Barangay Events and Announcements
                </h1>

                <form method="GET" action="{{ route('admin.announcements.index') }}"
                    class="d-flex align-items-center gap-1 ms-auto">

                    <div class="input-group" style="width:220px;">
                        <span class="input-group-text bg-white border-end-0 px-2">
                            <i class="fas fa-search text-muted" style="font-size:.75rem;"></i>
                        </span>
                        <input type="search" name="search"
                            value="{{ $search ?? '' }}"
                            class="form-control border-start-0 ps-0"
                            placeholder="Search announcement"
                            style="font-size:.82rem;">
                    </div>

                    <a href="{{ route('admin.announcements.index') }}"
                    class="btn btn-outline-secondary table-icon-btn"
                    title="Reset" aria-label="Reset search">
                        <i class="fas fa-rotate-left fa-fw"></i>
                    </a>

                    <a href="{{ route('admin.announcements.create') }}"
                    class="btn btn-primary table-icon-btn"
                    title="Add announcement" aria-label="Add announcement">
                        <i class="fas fa-plus fa-fw"></i>
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle admin-table resident-master-table mb-0">
                    <thead>
                        <tr>
                            <th>Caption</th>
                            <th>Event Date</th>
                            <th>Headline</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($announcements as $announcement)
                            <tr>
                                <td>
                                    @if ($announcement->caption_path)
                                        <img src="{{ asset('storage/' . $announcement->caption_path) }}" alt="{{ $announcement->headline }}" class="announcement-thumb">
                                    @else
                                        <span class="table-muted-text">No caption</span>
                                    @endif
                                </td>
                                <td class="table-code">{{ $announcement->event_date?->format('M d, Y') }}</td>
                                <td class="table-primary-text">{{ $announcement->headline }}</td>
                                <td class="resident-address-cell">{{ $announcement->description }}</td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary table-icon-btn" title="Edit announcement" aria-label="Edit announcement">
                                            <i class="fas fa-pen fa-fw"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="m-0" onsubmit="return confirm('Delete this announcement?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger table-icon-btn" title="Delete announcement" aria-label="Delete announcement">
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
                                        No announcements found.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="fs-7 text-muted">
                    Showing {{ $announcements->firstItem() ?? 0 }}-{{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }} entries
                </div>
                <div>{{ $announcements->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
