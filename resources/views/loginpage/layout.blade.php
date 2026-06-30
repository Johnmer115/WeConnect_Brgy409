<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeConnect Brgy 409 | Login</title>
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('image/brgy_logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --wc-blue: #10439f;
            --wc-blue-dark: #08245c;
            --wc-gold: #f4b400;
            --wc-green: #169b45;
            --wc-ink: #162033;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--wc-ink);
            font-family: "Instrument Sans", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(246, 249, 255, 0.94)),
                radial-gradient(circle at 18% 84%, rgba(16, 67, 159, 0.22), transparent 28%),
                radial-gradient(circle at 88% 78%, rgba(244, 180, 0, 0.18), transparent 24%);
        }

        .login-shell {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .login-shell::before,
        .login-shell::after {
            position: absolute;
            bottom: -18px;
            z-index: 0;
            color: rgba(16, 67, 159, 0.12);
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            line-height: 1;
            pointer-events: none;
        }

        .login-shell::before {
            left: clamp(12px, 7vw, 90px);
            content: "\f1ad";
            font-size: clamp(8rem, 22vw, 18rem);
        }

        .login-shell::after {
            right: clamp(18px, 9vw, 120px);
            content: "\f64f";
            font-size: clamp(6rem, 15vw, 13rem);
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: min(100%, 430px);
            border: 1px solid rgba(16, 67, 159, 0.12);
            border-radius: 8px;
            box-shadow: 0 18px 45px rgba(8, 36, 92, 0.12);
        }

        .brand-mark {
            display: inline-grid;
            width: 78px;
            height: 78px;
            place-items: center;
            border: 4px solid var(--wc-blue);
            border-radius: 50%;
            color: var(--wc-blue);
            background: #fff;
            box-shadow: inset 0 0 0 4px var(--wc-gold), 0 10px 24px rgba(16, 67, 159, 0.16);
        }

        .brand-mark i {
            font-size: 2rem;
        }

        .form-control {
            min-height: 44px;
            border-color: #c8d4ea;
            border-radius: 6px;
        }

        .form-control:focus {
            border-color: var(--wc-blue);
            box-shadow: 0 0 0 0.2rem rgba(16, 67, 159, 0.14);
        }

        .btn-primary {
            border-color: var(--wc-green);
            background: var(--wc-green);
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            border-color: #12813a;
            background: #12813a;
        }

        .top-line {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--wc-blue), var(--wc-gold), var(--wc-green));
        }
    </style>
</head>
<body>
<div class="top-line"></div>
<main>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</main>

</body>
</html>
