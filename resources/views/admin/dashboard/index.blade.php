@extends('admin.layouts.layout')

@section('title', 'Dashboard - WeConnect Brgy 409')
@section('page-title', 'Dashboard')
@section('flash-wrapper-class', 'px-3 pt-0')

@section('content')
<div class="container-fluid admin-page admin-page--dashboard">

    {{-- ══════════════════════════════════════════════════════════
         ROW 1 — STAT CARDS (unchanged)
    ══════════════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card dashboard-panel h-100">
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
            <div class="card dashboard-panel h-100">
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
            <div class="card dashboard-panel h-100">
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

    {{-- ══════════════════════════════════════════════════════════
         ROW 2 — BANNER (left) + OFFICIALS + DETAILS (right)
    ══════════════════════════════════════════════════════════ --}}
    <div class="row g-4 mb-4">

        {{-- ── LEFT: Announcement Banner ─────────────────────── --}}
        <div class="col-12 col-lg-8">
            <div class="card dashboard-panel h-100" id="banner-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="small text-uppercase text-muted fw-semibold mb-2">Barangay Banner</p>
                            <h2 class="h5 mb-2">Announcement Banner</h2>
                            <p class="text-muted fs-7 mb-0">Displayed on the resident-facing portal.</p>
                        </div>
                        @if(in_array(auth()->user()->role, ['secretary', 'chairman']))
                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                id="edit-banner-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#bannerModal">
                            <i class="fas fa-pen-to-square fa-fw me-1"></i> Edit Banner
                        </button>
                        @endif
                    </div>

                    {{-- Banner display --}}
                    <div id="banner-display">
                        <img src="{{ $banner ? asset('storage/' . $banner) : asset('image/brgy_banner.png') }}"
                             id="banner-img"
                             class="db-banner-img"
                             alt="Announcement Banner">
                    </div>

                    {{-- Upload success alert --}}
                    <div class="alert alert-success alert-dismissible fade mt-3 mb-0 py-2 small" id="banner-alert" role="alert" style="display:none!important">
                        <i class="fas fa-check-circle me-1"></i>
                        <span id="banner-alert-msg">Banner updated successfully.</span>
                        <button type="button" class="btn-close btn-sm" onclick="document.getElementById('banner-alert').style.display='none!important'"></button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT COLUMN ─────────────────────────────────── --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-4">

            {{-- Barangay Details card --}}
            <div class="card dashboard-panel">
                <div class="card-body p-3">
                    <p class="small text-uppercase text-muted fw-semibold mb-2">Barangay Details</p>
                    <h2 class="h5 mb-2">Barangay 409, Manila City</h2>
                    <p class="text-muted fs-7 mb-3">Central admin panel for residents, certificate requests, reports, logs, and account access.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users fa-fw me-1"></i> Manage Residents
                        </a>
                        <a href="{{ route('admin.certificates.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-signature fa-fw me-1"></i> Issue Certificates
                        </a>
                        <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user-shield fa-fw me-1"></i> Admin Accounts
                        </a>
                    </div>
                </div>
            </div>

            {{-- Barangay Official Staff card --}}
            <div class="card dashboard-panel">
                <div class="card-body p-3">
                    <p class="small text-uppercase text-muted fw-semibold mb-2">Barangay Staff</p>
                    <h2 class="h5 mb-2">Official Staff</h2>
                    <p class="text-muted fs-7 mb-3">Barangay officers and elected SK officials.</p>

                    @if($officials->isEmpty())
                        <p class="text-muted small mb-0">No officials on record.
                            <em class="d-block mt-1 text-secondary" style="font-size:.75rem">
                                (Seed via Tinker or wait for management UI)
                            </em>
                        </p>
                    @else
                        {{-- Barangay officials --}}
                        @if($officials->has('barangay'))
                            <p class="db-official-group-label">Sangguniang Barangay</p>
                            @foreach($officials['barangay'] as $o)
                                <div class="db-official-row">
                                    <span class="db-official-position">{{ $o->position }}</span>
                                    <span class="db-official-name">{{ $o->name }}</span>
                                </div>
                            @endforeach
                        @endif

                        {{-- SK officials --}}
                        @if($officials->has('sk'))
                            <p class="db-official-group-label mt-2">Sangguniang Kabataan</p>
                            @foreach($officials['sk'] as $o)
                                <div class="db-official-row">
                                    <span class="db-official-position">{{ $o->position }}</span>
                                    <span class="db-official-name">{{ $o->name }}</span>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>

        </div>{{-- /right col --}}
    </div>

    {{-- ══════════════════════════════════════════════════════════
         ROW 3 — TIMELINE (left) + CALENDAR (right)
    ══════════════════════════════════════════════════════════ --}}
    <div class="row g-4">

        {{-- ── LEFT: Event Timeline ─────────────────────────── --}}
        <div class="col-12 col-lg-5">
            <div class="card dashboard-panel h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <p class="small text-uppercase text-muted fw-semibold mb-2">Barangay Updates</p>
                            <h2 class="h5 mb-2">Upcoming Events &amp; Activities</h2>
                            <p class="text-muted fs-7 mb-0">Official announcements scheduled on the calendar.</p>
                        </div>
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-bullhorn fa-fw me-1"></i> Manage
                        </a>
                    </div>

                    <div id="timeline-container">
                        @forelse($upcomingEvents as $event)
                            <div class="db-timeline-item">
                                <div class="db-timeline-dot"></div>
                                <div class="db-timeline-body">
                                    <div class="db-timeline-date">
                                        {{ $event->event_date->format('M d, Y') }}
                                        @if($event->event_date->isToday())
                                            <span class="badge text-bg-primary ms-1" style="font-size:.65rem">Today</span>
                                        @endif
                                    </div>
                                    <div class="db-timeline-title">{{ $event->headline }}</div>
                                    @if($event->description)
                                        <div class="db-timeline-desc">{{ Str::limit($event->description, 90) }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="db-empty-state">
                                <i class="fas fa-calendar-xmark fa-2x mb-2 text-muted"></i>
                                <p class="text-muted small mb-0">No upcoming events.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Activity Calendar ─────────────────────── --}}
        <div class="col-12 col-lg-7">
            <div class="card dashboard-panel h-100">
                <div class="card-body p-3">
                    <p class="small text-uppercase text-muted fw-semibold mb-2">Barangay Calendar</p>
                    <h2 class="h5 mb-2">Activity Calendar</h2>
                    <p class="text-muted fs-7 mb-3">Monthly schedule of barangay events and activities.</p>
                    <div id="dashboard-calendar"></div>
                </div>
            </div>
        </div>

    </div>{{-- /row 3 --}}
</div>{{-- /container --}}

{{-- ══════════════════════════════════════════════════════════
     BANNER MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold" id="bannerModalLabel">
                    <i class="fas fa-image fa-fw text-primary me-1"></i>
                    Update Announcement Banner
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">

                {{-- Current / Preview --}}
                <div class="mb-3" id="modal-preview-wrap">
                    <p class="small text-muted mb-1">Preview:</p>
                    <img src="{{ $banner ? asset('storage/' . $banner) : asset('image/brgy_banner.png') }}"
                         id="modal-preview-img"
                         class="db-banner-img"
                         alt="Banner Preview">
                </div>

                {{-- File input --}}
                <div class="mb-2">
                    <label for="banner-file-input" class="form-label small fw-semibold">Choose Image</label>
                    <input type="file"
                           class="form-control form-control-sm"
                           id="banner-file-input"
                           accept="image/*">
                    <div class="form-text">JPG, PNG, WEBP, GIF — max 5 MB</div>
                </div>

                <div class="alert alert-danger alert-dismissible py-2 small d-none" id="banner-modal-error" role="alert">
                    <span id="banner-error-msg"></span>
                    <button type="button" class="btn-close btn-sm" onclick="this.parentElement.classList.add('d-none')"></button>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger {{ $banner ? '' : 'd-none' }}" id="delete-banner-btn">
                        <i class="fas fa-trash-alt fa-fw me-1"></i> Remove Banner
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary" id="save-banner-btn">
                        <i class="fas fa-save fa-fw me-1"></i> Save Banner
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- FullCalendar v6 (community, CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
(function () {
    'use strict';

    var EVENTS_URL  = '{{ route('admin.dashboard.events') }}';
    var UPLOAD_URL  = '{{ route('admin.dashboard.banner.upload') }}';
    var DELETE_URL  = '{{ route('admin.dashboard.banner.delete') }}';
    var CSRF_TOKEN  = '{{ csrf_token() }}';

    // ── FullCalendar ─────────────────────────────────────────────
    var calEl = document.getElementById('dashboard-calendar');
    if (calEl) {
        var calendar = new FullCalendar.Calendar(calEl, {
            initialView:    'dayGridMonth',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  '',
            },
            height:       'auto',
            eventColor:   '#2f80d1',
            events:       EVENTS_URL,
            eventDidMount: function (info) {
                // Bootstrap tooltip on event
                info.el.setAttribute('title', info.event.title);
                info.el.setAttribute('data-bs-toggle', 'tooltip');
                info.el.setAttribute('data-bs-placement', 'top');
                new bootstrap.Tooltip(info.el);
            },
        });
        calendar.render();
    }

    // ── Banner file preview ──────────────────────────────────────
    var fileInput     = document.getElementById('banner-file-input');
    var previewImg    = document.getElementById('modal-preview-img');
    var previewHolder = document.getElementById('modal-preview-placeholder');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                if (previewHolder) previewHolder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Save Banner ──────────────────────────────────────────────
    var saveBtn = document.getElementById('save-banner-btn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function () {
            var file = fileInput ? fileInput.files[0] : null;
            if (!file) {
                showModalError('Please select an image file.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showModalError('File size must be 5 MB or less.');
                return;
            }

            var formData = new FormData();
            formData.append('banner', file);
            formData.append('_token', CSRF_TOKEN);

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin fa-fw me-1"></i> Saving…';

            fetch(UPLOAD_URL, { method: 'POST', body: formData })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        refreshBanner(data.url);
                        closeBannerModal();
                        showPageAlert('Banner updated successfully.');
                        if (deleteBtn) {
                            deleteBtn.classList.remove('d-none');
                        }
                    } else {
                        showModalError('Upload failed. Please try again.');
                    }
                })
                .catch(function () { showModalError('Network error. Please try again.'); })
                .finally(function () {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save fa-fw me-1"></i> Save Banner';
                });
        });
    }

    // ── Delete Banner ────────────────────────────────────────────
    var deleteBtn = document.getElementById('delete-banner-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            if (!confirm('Remove the current banner?')) return;
            deleteBtn.disabled = true;

            fetch(DELETE_URL, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    clearBanner();
                    closeBannerModal();
                    showPageAlert('Banner removed.');
                    if (deleteBtn) {
                        deleteBtn.classList.add('d-none');
                    }
                }
            })
            .catch(function () {})
            .finally(function () { deleteBtn.disabled = false; });
        });
    }

    // ── Helpers ──────────────────────────────────────────────────
    function refreshBanner(url) {
        var display = document.getElementById('banner-display');
        if (!display) return;
        display.innerHTML = '<img src="' + url + '" id="banner-img" class="db-banner-img" alt="Announcement Banner">';
    }

    function clearBanner() {
        var display = document.getElementById('banner-display');
        if (!display) return;
        display.innerHTML = '<img src="' + '{{ asset('image/brgy_banner.png') }}' + '" id="banner-img" class="db-banner-img" alt="Announcement Banner">';

        // Also update the modal preview image
        var previewImg = document.getElementById('modal-preview-img');
        if (previewImg) {
            previewImg.src = '{{ asset('image/brgy_banner.png') }}';
        }
    }

    function closeBannerModal() {
        var modal = bootstrap.Modal.getInstance(document.getElementById('bannerModal'));
        if (modal) modal.hide();
        if (fileInput) fileInput.value = '';
    }

    function showModalError(msg) {
        var el = document.getElementById('banner-modal-error');
        var msgEl = document.getElementById('banner-error-msg');
        if (el && msgEl) { msgEl.textContent = msg; el.classList.remove('d-none'); }
    }

    function showPageAlert(msg) {
        var el = document.getElementById('banner-alert');
        var msgEl = document.getElementById('banner-alert-msg');
        if (el && msgEl) {
            msgEl.textContent = msg;
            el.style.removeProperty('display');
            el.classList.add('show');
            setTimeout(function () {
                el.classList.remove('show');
                setTimeout(function () { el.style.display = 'none'; }, 400);
            }, 3500);
        }
    }
})();
</script>
@endpush
