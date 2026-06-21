<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeConnect | Admin Dashboard</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" style="font-family: 'Instrument Sans', system-ui, sans-serif;">
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container">
            <span class="navbar-brand fw-semibold">WeConnect Admin</span>
            <div class="d-flex align-items-center gap-3">
                <span class="small text-muted">{{ auth()->user()->name }} | {{ ucfirst(auth()->user()->role) }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="bg-white border rounded-2 p-4">
            <p class="text-uppercase small text-primary fw-semibold mb-2">Barangay 409 - Zone 42</p>
            <h1 class="h3 mb-2">Admin Dashboard</h1>
            <p class="text-muted mb-0">Login is working. Resident, certificate, announcement, and account modules can plug in here next.</p>
        </div>
    </main>
</body>
</html>
