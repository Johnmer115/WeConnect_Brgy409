@extends('admin.layouts.layout')

@section('title', 'Announcements - WeConnect Brgy 409')
@section('page-title', 'Announcements')
@section('flash-wrapper-class', 'd-none')

@section('content')
<div class="container-fluid admin-page cert-page">
    <div class="cert-header">
        <div>
            <h1 class="cert-title">Barangay Events and Announcements</h1>
            <p class="cert-subtitle">Create and manage upcoming activities and news for the barangay</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="cert-btn-add" title="Add Announcement" aria-label="Add Announcement">
            <i class="fas fa-plus fa-fw"></i>
            Add Announcement
        </a>
    </div>

    @if(session('success'))
        <div class="cert-alert cert-alert-success" id="cert-flash-success">
            <i class="fas fa-check-circle fa-fw"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="cert-filters-bar">
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="cert-filters-form" id="announcement-filter-form">
            <div class="cert-filter-group cert-filter-group--search">
                <label class="cert-filter-label" for="announcement-search">Search</label>
                <div class="input-group cert-search-group">
                    <span class="input-group-text">
                        <i class="fas fa-search fa-fw"></i>
                    </span>
                    <input type="search"
                           id="announcement-search"
                           name="search"
                           value="{{ $search ?? '' }}"
                           class="form-control cert-search-input"
                           placeholder="Search announcement">
                    <button type="submit" class="cert-search-btn" title="Search" aria-label="Search">
                        Search
                    </button>
                </div>
            </div>
        </form>
        <div class="cert-total-badge">
            {{ $announcements->total() }} announcement{{ $announcements->total() !== 1 ? 's' : '' }}
        </div>
    </div>

    <div class="cert-table-card">
        <div class="table-responsive">
            <table class="cert-table" id="announcement-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">Caption</th>
                        <th style="width: 15%;">Event Date</th>
                        <th style="width: 25%;">Headline</th>
                        <th style="width: 36%;">Description</th>
                        <th style="width: 12%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr id="announcement-row-{{ $announcement->id }}">
                            <td>
                                @if ($announcement->caption_path)
                                    <img src="{{ asset('storage/' . $announcement->caption_path) }}" alt="{{ $announcement->headline }}" class="announcement-thumb" style="max-height: 48px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1);">
                                @else
                                    <span class="text-muted small">No caption</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-muted small">{{ $announcement->event_date?->format('M d, Y') ?? 'N/A' }}</span>
                            </td>
                            <td class="cert-td-name">
                                {{ $announcement->headline }}
                            </td>
                            <td class="cert-td-address" title="{{ $announcement->description }}">
                                {{ Str::limit($announcement->description, 100) }}
                            </td>
                            <td>
                                <div class="cert-action-group justify-content-center">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                       class="cert-action-btn cert-action-edit"
                                       title="Edit Announcement"
                                       aria-label="Edit Announcement">
                                        <i class="fas fa-pen-to-square fa-fw"></i>
                                    </a>
                                    <button type="button"
                                            class="cert-action-btn cert-action-delete btn-delete-announcement"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteAnnouncementModal"
                                            data-headline="{{ $announcement->headline }}"
                                            data-action-url="{{ route('admin.announcements.destroy', $announcement) }}"
                                            title="Delete Announcement"
                                            aria-label="Delete Announcement">
                                        <i class="fas fa-trash-can fa-fw"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="cert-empty">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                No announcements found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="cert-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Showing {{ $announcements->firstItem() ?? 0 }} to {{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }} announcements
            </div>
            @if($announcements->hasPages())
                <div>
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── DELETE ANNOUNCEMENT CONFIRMATION MODAL ────────────────────── --}}
<div class="modal fade" id="deleteAnnouncementModal" tabindex="-1" aria-labelledby="deleteAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-confirm-delete modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteAnnouncementModalLabel">
                    <i class="fas fa-triangle-exclamation fa-lg"></i>
                    <span>Delete Announcement</span>
                </h5>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="mb-2 text-dark" style="font-size: 0.95rem; line-height: 1.5;">
                    Are you sure you want to delete the announcement <strong id="delete-announcement-headline"></strong>?
                </p>
                <hr class="my-3" style="border-color: #e2e8f0; opacity: 0.8;">
                <p class="mb-0 text-danger small" style="font-style: italic; font-weight: 500;">
                    <i class="fas fa-triangle-exclamation fa-fw me-1"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer border-0 pb-4 pt-2 px-4">
                <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal" style="border-radius: 8px; color: #475569;">Cancel</button>
                <form id="delete-announcement-form" method="POST" action="" class="d-inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold px-4" style="border-radius: 8px; background: #dc2626; border-color: #dc2626;">Yes, Delete Announcement</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        'use strict';
        const deleteForm = document.getElementById('delete-announcement-form');
        const deleteHeadline = document.getElementById('delete-announcement-headline');

        document.querySelectorAll('.btn-delete-announcement').forEach(button => {
            button.addEventListener('click', function () {
                const headline = this.getAttribute('data-headline');
                const actionUrl = this.getAttribute('data-action-url');

                if (deleteForm && deleteHeadline) {
                    deleteForm.setAttribute('action', actionUrl);
                    deleteHeadline.textContent = headline;
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
