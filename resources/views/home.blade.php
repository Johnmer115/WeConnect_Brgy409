@extends('resident.layout')

@section('title', 'WeConnect | Home')

@section('resident-content')
    @unless ($resident->verified_at)
        <div class="bg-white border rounded-2 shadow-sm p-4 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div>
                    <p class="text-uppercase small text-primary fw-semibold mb-2">Resident Details</p>
                    <h1 class="h3 mb-1">{{ $resident->full_name }}</h1>
                    <p class="text-muted mb-0">{{ $resident->full_address }}</p>
                </div>
                <div>
                    <span class="badge rounded-pill text-bg-warning px-3 py-2">Pending Approval</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="bg-white border rounded-2 shadow-sm p-4 h-100">
                    <h2 class="h6 fw-semibold mb-3">Basic Information</h2>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Last Name</div>
                            <div class="fw-semibold">{{ $resident->last_name }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">First Name</div>
                            <div class="fw-semibold">{{ $resident->first_name }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Middle Name</div>
                            <div class="fw-semibold">{{ $resident->middle_name ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Suffix</div>
                            <div class="fw-semibold">{{ $resident->suffix ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Date of Birth</div>
                            <div class="fw-semibold">{{ $resident->date_of_birth?->format('F d, Y') }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Age</div>
                            <div class="fw-semibold">{{ $resident->age }} years old</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Gender</div>
                            <div class="fw-semibold">{{ $resident->gender }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Blood Type</div>
                            <div class="fw-semibold">{{ $resident->blood_type ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Religion</div>
                            <div class="fw-semibold">{{ $resident->religion ?? '-' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-muted small">Health Status</div>
                            <div class="fw-semibold">{{ $resident->health_status }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="bg-white border rounded-2 shadow-sm p-4 h-100">
                    <h2 class="h6 fw-semibold mb-3">Contact and Address</h2>
                    <div class="d-grid gap-3">
                        <div>
                            <div class="text-muted small">Email Address</div>
                            <div class="fw-semibold">{{ $resident->email ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Mobile Number</div>
                            <div class="fw-semibold">{{ $resident->mobile_number ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Telephone Number</div>
                            <div class="fw-semibold">{{ $resident->telephone_number ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Home Address</div>
                            <div class="fw-semibold">{{ $resident->full_address }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Purok</div>
                            <div class="fw-semibold">{{ $resident->purok->name ?? 'Unassigned' }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Approval Status</div>
                            <div class="fw-semibold">Waiting for admin approval</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @php
            $officialStaff = [
                ['initials' => 'BC', 'name' => 'Barangay Captain', 'role' => 'Punong Barangay'],
                ['initials' => 'BS', 'name' => 'Barangay Secretary', 'role' => 'Administrative Staff'],
                ['initials' => 'BT', 'name' => 'Barangay Treasurer', 'role' => 'Finance Staff'],
                ['initials' => 'BC', 'name' => 'Barangay Clerk', 'role' => 'Front Desk Staff'],
                ['initials' => 'HW', 'name' => 'Barangay Health Worker', 'role' => 'Community Health Staff'],
                ['initials' => 'TL', 'name' => 'Barangay Tanod Lead', 'role' => 'Safety and Security Staff'],
            ];

            $skOfficials = [
                ['initials' => 'SC', 'name' => 'SK Chairperson', 'role' => 'Youth Council Lead'],
                ['initials' => 'SS', 'name' => 'SK Secretary', 'role' => 'Youth Council Staff'],
                ['initials' => 'ST', 'name' => 'SK Treasurer', 'role' => 'Youth Council Finance'],
                ['initials' => 'SK', 'name' => 'SK Kagawad', 'role' => 'Youth Council Officer'],
            ];

        @endphp

        <style>
            .home-hero-grid {
                display: grid;
                grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
                gap: 0.8rem;
                min-height: 0;
            }

            .home-fit {
                display: grid;
                grid-template-rows: minmax(0, 1fr) auto;
                gap: 0.8rem;
                min-height: calc(100vh - 8.25rem);
            }

            .official-photo {
                position: relative;
                min-height: 0;
                height: 100%;
                overflow: hidden;
                border: 1px solid var(--wc-blue-line);
                border-radius: 0.7rem;
                background:
                    linear-gradient(135deg, rgba(22, 79, 134, 0.92), rgba(47, 128, 209, 0.84)),
                    linear-gradient(0deg, #dceeff, #ffffff);
            }

            .official-photo-stage {
                position: absolute;
                inset: auto 1rem 1rem;
                display: grid;
                grid-template-columns: repeat(6, 1fr);
                gap: 0.45rem;
                align-items: end;
            }

            .official-portrait {
                display: grid;
                min-height: 88px;
                place-items: center;
                border: 1px solid rgba(255, 255, 255, 0.4);
                border-radius: 0.55rem;
                color: #ffffff;
                background: rgba(255, 255, 255, 0.18);
                font-weight: 800;
            }

            .official-portrait:nth-child(even) {
                min-height: 72px;
            }

            .official-photo-caption {
                position: relative;
                z-index: 1;
                max-width: 31rem;
                padding: 1rem;
                color: #ffffff;
            }

            .official-directory {
                min-height: 0;
                overflow: hidden;
            }

            .official-list {
                display: grid;
                gap: 0.4rem;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .official-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                min-height: 2.35rem;
                padding: 0.42rem 0.55rem;
                border: 1px solid #edf1f5;
                border-radius: 0.55rem;
                background: #f8fbff;
            }

            .official-avatar {
                display: grid;
                width: 1.8rem;
                height: 1.8rem;
                flex: 0 0 auto;
                place-items: center;
                border-radius: 50%;
                color: var(--wc-blue-dark);
                background: #dceeff;
                font-size: 0.78rem;
                font-weight: 800;
            }

            .news-card {
                min-height: 100%;
                border: 1px solid var(--wc-blue-line);
                border-radius: 0.65rem;
                background: #ffffff;
            }

            .home-news {
                padding: 1rem !important;
            }

            .home-news .news-card {
                min-height: 5.5rem;
            }

            .home-news-image {
                width: 4.5rem;
                aspect-ratio: 16 / 9;
                object-fit: cover;
                border: 1px solid var(--wc-blue-line);
                border-radius: 0.4rem;
                background: #f8fbff;
            }

            @media (max-width: 991.98px) {
                .home-fit {
                    min-height: auto;
                }

                .home-hero-grid {
                    grid-template-columns: 1fr;
                }

                .official-photo {
                    min-height: 18rem;
                }
            }
        </style>

        <div class="home-fit">
            <div class="home-hero-grid">
                <section class="official-photo shadow-sm">
                    <div class="official-photo-caption">
                        <p class="text-uppercase small fw-semibold mb-1">Barangay 409</p>
                        <h1 class="h4 mb-1">Barangay Official Staff</h1>
                        <p class="mb-0 small opacity-75">Landscape official staff photo area</p>
                    </div>
                    <div class="official-photo-stage" aria-hidden="true">
                        <div class="official-portrait">PB</div>
                        <div class="official-portrait">SEC</div>
                        <div class="official-portrait">TR</div>
                        <div class="official-portrait">SK</div>
                        <div class="official-portrait">HW</div>
                        <div class="official-portrait">TN</div>
                    </div>
                </section>

                <aside class="official-directory bg-white border rounded-2 shadow-sm p-3">
                    <p class="text-uppercase small text-primary fw-semibold mb-1">Directory</p>
                    <h2 class="h6 fw-semibold mb-2">Barangay Official Staff</h2>

                    <h3 class="small fw-semibold mb-2">Barangay Officers and Staff</h3>
                    <ul class="official-list mb-2">
                        @foreach ($officialStaff as $official)
                            <li class="official-item">
                                <span class="official-avatar">{{ $official['initials'] }}</span>
                                <span>
                                    <span class="d-block fw-semibold small">{{ $official['name'] }}</span>
                                    <span class="d-block text-muted small">{{ $official['role'] }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <h3 class="small fw-semibold mb-2">Elected SK Barangay Officers</h3>
                    <ul class="official-list">
                        @foreach ($skOfficials as $official)
                            <li class="official-item">
                                <span class="official-avatar">{{ $official['initials'] }}</span>
                                <span>
                                    <span class="d-block fw-semibold small">{{ $official['name'] }}</span>
                                    <span class="d-block text-muted small">{{ $official['role'] }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </aside>
            </div>

            <section class="home-news bg-white border rounded-2 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-2">
                    <div>
                        <p class="text-uppercase small text-primary fw-semibold mb-1">Updates</p>
                        <h2 class="h6 fw-semibold mb-0">News and Announcements</h2>
                    </div>
                    <a href="{{ route('resident.announcements') }}" class="btn btn-outline-primary btn-sm align-self-md-start">
                        View Announcements
                    </a>
                </div>

                <div class="row g-2">
                    @forelse ($announcements as $announcement)
                        <div class="col-12 col-md-6">
                            <article class="news-card d-flex gap-2 p-2">
                                @if ($announcement->caption_path)
                                    <img src="{{ asset('storage/' . $announcement->caption_path) }}" alt="{{ $announcement->headline }}" class="home-news-image">
                                @endif
                                <div>
                                    <div class="text-muted small mb-1">{{ $announcement->event_date?->format('M d, Y') }}</div>
                                    <h3 class="small fw-semibold mb-1">{{ $announcement->headline }}</h3>
                                    <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($announcement->description, 95) }}</p>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="news-card p-3 text-muted small">No news or announcements posted yet.</div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    @endunless
@endsection
