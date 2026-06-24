@extends('resident.layout')

@section('title', 'WeConnect | Announcements')

@section('resident-content')
    <div class="bg-white border rounded-2 shadow-sm p-4 mb-4">
        <p class="text-uppercase small text-primary fw-semibold mb-2">Resident Module</p>
        <h1 class="h3 mb-1">Announcements</h1>
        <p class="text-muted mb-0">Barangay events, advisories, and resident updates.</p>
    </div>

    <div class="row g-3">
        @forelse ($announcements as $announcement)
            <div class="col-12 col-md-6 col-xl-4">
                <article class="bg-white border rounded-2 shadow-sm h-100 overflow-hidden">
                    @if ($announcement->caption_path)
                        <img src="{{ asset('storage/' . $announcement->caption_path) }}" alt="{{ $announcement->headline }}" class="w-100" style="aspect-ratio:16/9; object-fit:cover;">
                    @endif
                    <div class="p-3">
                        <div class="text-muted small mb-2">{{ $announcement->event_date?->format('F d, Y') }}</div>
                        <h2 class="h6 fw-semibold mb-2">{{ $announcement->headline }}</h2>
                        <p class="text-muted small mb-0">{{ $announcement->description }}</p>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="bg-white border rounded-2 shadow-sm p-4 text-center text-muted">
                    <div class="fw-semibold mb-1">Nothing to show yet</div>
                    <div class="small">Please check back later for official barangay announcements.</div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $announcements->links() }}
    </div>
@endsection
