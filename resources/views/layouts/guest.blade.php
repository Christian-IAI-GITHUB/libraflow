<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="/images/bg-login.jpg">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
        }

        /* Moitié gauche — image */
        .login-left {
            flex: 1;
            background-image: url('/images/bg-login.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            display: none;
        }

        @media (min-width: 768px) {
            .login-left { display: block; }
        }

        /* Overlay sur l'image */
        .login-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(30, 58, 95, 0.55) 0%,
                rgba(21, 42, 71, 0.35) 100%
            );
        }

        /* Texte par-dessus l'image */
        .login-left-content {
            position: absolute;
            bottom: 48px;
            left: 40px;
            right: 40px;
            z-index: 1;
            color: #fff;
        }

        .login-left-content h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 8px;
            letter-spacing: -0.02em;
        }

        .login-left-content p {
            font-size: 0.95rem;
            opacity: 0.8;
            margin: 0;
            line-height: 1.6;
        }

        /* Moitié droite — formulaire */
        .login-right {
            width: 100%;
            max-width: 480px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 40px;
            box-shadow: -4px 0 24px rgba(0,0,0,0.06);
        }

        @media (max-width: 767px) {
            .login-right {
                max-width: 100%;
                padding: 32px 24px;
            }
        }

        /* Logo */
        .login-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 36px;
        }

        .login-logo-icon {
            width: 36px;
            height: 36px;
            background: #1e3a5f;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-logo-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .login-logo-text {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e3a5f;
            letter-spacing: -0.02em;
        }
    </style>
</head>
<body>

    
    <div class="login-left">
        <div class="login-left-content">
            <h2>Bienvenue sur LibraFlow</h2>
            <p>Gérez vos emprunts, réservations et catalogue en toute simplicité.</p>
        </div>
    </div>

    
    <div class="login-right">

        {{-- Logo --}}
        <div class="login-logo">
            <div class="login-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                </svg>
            </div>
            <span class="login-logo-text">LibraFlow</span>
        </div>

        {{-- Contenu Breeze --}}
        {{ $slot }}

    </div>

</body>
</html>