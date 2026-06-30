<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificate->type_label }} - WeConnect Brgy 409</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=IM+Fell+English:ital@0;1&family=Inter:wght@400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #111;
            padding: 0;
        }

        /* ── Print page setup ──────────────────────────────────── */
        @page {
            size: A4 portrait;
            margin: 1.8cm 2cm;
        }

        /* ── Screen wrapper ────────────────────────────────────── */
        .print-page {
            width: 21cm;
            min-height: 29.7cm;
            margin: 0 auto;
            padding: 2cm;
            background: #fff;
            position: relative;
        }

        /* ── Screen controls ────────────────────────────────────── */
        .screen-controls {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .screen-controls a,
        .screen-controls button {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back-list {
            background: #f1f5f9;
            color: #334155;
        }

        .btn-print {
            background: #1d4ed8;
            color: #fff;
        }

        @media print {
            .screen-controls { display: none; }
            .print-page { width: 100%; padding: 0; margin: 0; }
        }

        /* ── Header ─────────────────────────────────────────────── */
        .cert-letterhead {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .cert-letterhead img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .cert-letterhead-text {
            flex: 1;
            text-align: center;
        }

        .cert-letterhead-text .republic {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #64748b;
        }

        .cert-letterhead-text .brgy {
            font-size: 18px;
            font-weight: 700;
            color: #1d4ed8;
            line-height: 1.2;
        }

        .cert-letterhead-text .city {
            font-size: 11px;
            color: #475569;
        }

        /* ── Certificate Title ──────────────────────────────────── */
        .cert-doc-title {
            text-align: center;
            margin: 28px 0 24px;
        }

        .cert-doc-title h1 {
            font-family: 'IM Fell English', serif;
            font-size: 28px;
            color: #1e293b;
            letter-spacing: 1px;
        }

        .cert-doc-title .cert-doc-underline {
            width: 120px;
            height: 2px;
            background: #1d4ed8;
            margin: 8px auto 0;
            border-radius: 99px;
        }

        /* ── Body Text ──────────────────────────────────────────── */
        .cert-body {
            font-size: 13.5px;
            line-height: 1.85;
            text-align: justify;
            color: #1e293b;
        }

        .cert-body p { margin-bottom: 14px; }

        .cert-body strong {
            font-weight: 700;
            font-size: 14px;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        /* ── Info Table ─────────────────────────────────────────── */
        .cert-info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }

        .cert-info-table td {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
        }

        .cert-info-table td:first-child {
            font-weight: 600;
            width: 180px;
            background: #f8fafc;
            color: #475569;
        }

        /* ── Purpose / details block ────────────────────────────── */
        .cert-purpose-block {
            background: #f0f4ff;
            border-left: 4px solid #1d4ed8;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            margin: 20px 0;
            font-size: 13px;
            color: #1e3a8a;
        }

        /* ── Signature Block ────────────────────────────────────── */
        .cert-signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            gap: 32px;
        }

        .cert-sig-block {
            text-align: center;
            flex: 1;
        }

        .cert-sig-line {
            border-top: 1px solid #1e293b;
            margin-bottom: 6px;
        }

        .cert-sig-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cert-sig-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        /* ── Footer note ────────────────────────────────────────── */
        .cert-footer-note {
            margin-top: 48px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
            font-size: 10.5px;
            color: #94a3b8;
            text-align: center;
        }

        /* ── Control Number ─────────────────────────────────────── */
        .cert-control-no {
            position: absolute;
            top: 2cm;
            right: 2cm;
            font-size: 10px;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    {{-- Screen controls (hidden on print) --}}
    <div class="screen-controls">
        <a href="{{ route('admin.certificates.index') }}" class="btn-back-list">
            ← Back to List
        </a>
        <button class="btn-print" onclick="window.print()">
            🖨️ Print Certificate
        </button>
    </div>

    <div class="print-page">
        <div class="cert-control-no">
            Control No.: CERT-{{ str_pad($certificate->id, 5, '0', STR_PAD_LEFT) }}
        </div>

        {{-- Letterhead --}}
        <div class="cert-letterhead">
            <img src="{{ asset('image/logo/arellano_logo.png') }}" alt="Barangay 409 Seal" onerror="this.style.display='none'">
            <div class="cert-letterhead-text">
                <div class="republic">Republic of the Philippines</div>
                <div class="republic">City of Manila &mdash; Barangay 409</div>
                <div class="brgy">Barangay 409</div>
                <div class="city">Manila City, National Capital Region</div>
            </div>
            <img src="{{ asset('image/logo/arellano_logo.png') }}" alt="Barangay 409 Seal" onerror="this.style.display='none'">
        </div>

        {{-- Title --}}
        <div class="cert-doc-title">
            <h1>{{ strtoupper($certificate->type_label) }}</h1>
            <div class="cert-doc-underline"></div>
        </div>

        {{-- Body --}}
        <div class="cert-body">
            <p>To Whom It May Concern:</p>

            <p>
                This is to certify that <strong>{{ $certificate->full_name }}</strong>,
                @if($certificate->age) {{ $certificate->age }} year{{ $certificate->age != 1 ? 's' : '' }} of age, @endif
                @if($certificate->gender) {{ $certificate->gender }}, @endif
                a resident of <strong>{{ $certificate->full_address }}</strong>,
                is personally known to this office and that the above stated information
                is true and correct to the best of our knowledge.
            </p>

            @if($certificate->certificate_type === 'indigency')
            <p>
                This further certifies that the aforementioned individual belongs to
                the indigent sector of our barangay and is in need of assistance.
            </p>
            @elseif($certificate->certificate_type === 'barangay_clearance')
            <p>
                This further certifies that the aforementioned individual has no
                pending case or complaint filed against them in this barangay to the
                best of our knowledge, and is of good moral character and good standing
                in the community.
            </p>
            @elseif($certificate->certificate_type === 'residency')
            <p>
                This further certifies that the aforementioned individual is a bonafide
                resident of Barangay 409, Manila City, Philippines.
            </p>
            @else
            <p>
                This certification is issued upon the request of the above-named individual
                for whatever legal purpose it may serve.
            </p>
            @endif

            @if($certificate->purpose)
            <div class="cert-purpose-block">
                <strong>Purpose:</strong> {{ $certificate->purpose }}
            </div>
            @endif

            <p>
                This certification is issued this
                <strong>{{ now()->format('jS') }} day of {{ now()->format('F Y') }}</strong>,
                at Barangay 409, Manila City, Philippines, upon request of the interested party.
            </p>
        </div>

        <table class="cert-info-table">
            <tr>
                <td>Full Name</td>
                <td>{{ $certificate->full_name }}</td>
            </tr>
            @if($certificate->date_of_birth)
            <tr>
                <td>Date of Birth</td>
                <td>{{ $certificate->date_of_birth->format('F j, Y') }}{{ $certificate->age ? ' (Age: ' . $certificate->age . ')' : '' }}</td>
            </tr>
            @endif
            @if($certificate->gender)
            <tr>
                <td>Gender</td>
                <td>{{ $certificate->gender }}</td>
            </tr>
            @endif
            <tr>
                <td>Address</td>
                <td>{{ $certificate->full_address }}</td>
            </tr>
            <tr>
                <td>Certificate Type</td>
                <td>{{ $certificate->type_label }}</td>
            </tr>
            @if($certificate->purpose)
            <tr>
                <td>Purpose</td>
                <td>{{ $certificate->purpose }}</td>
            </tr>
            @endif
            <tr>
                <td>Date Requested</td>
                <td>{{ $certificate->created_at->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td>Date Issued</td>
                <td>{{ $certificate->issued_at ? $certificate->issued_at->format('F j, Y') : now()->format('F j, Y') }}</td>
            </tr>
            <tr>
                <td>Control No.</td>
                <td><strong>CERT-{{ str_pad($certificate->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            </tr>
        </table>

        {{-- Signature Block --}}
        <div class="cert-signature-section">
            <div class="cert-sig-block">
                <div class="cert-sig-name">&nbsp;</div>
                <div class="cert-sig-line"></div>
                <div class="cert-sig-label">Requesting Party's Signature</div>
            </div>
            <div class="cert-sig-block">
                <div class="cert-sig-name">HON. BARANGAY CHAIRMAN</div>
                <div class="cert-sig-line"></div>
                <div class="cert-sig-label">Barangay Chairman / Secretary</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="cert-footer-note">
            This document is computer-generated and is valid without signature only when verified
            through official Barangay 409 channels. &mdash; WeConnect Brgy 409 &copy; 2026
        </div>
    </div>
</body>
</html>
