<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LibraFlow — @yield('title', 'Bibliothèque')</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="/images/bg-library.jpg">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --color-primary: #1e3a5f;
            --color-primary-light: #2d4a6f;
            --color-primary-dark: #152a47;
            --color-accent: #10b981;
            --color-accent-light: #34d399;
            --color-surface: #ffffff;
            --color-surface-elevated: #f8fafc;
            --color-border: #e2e8f0;
            --color-text: #1e293b;
            --color-text-muted: #64748b;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-error: #ef4444;
        }
        
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        body {
            color: var(--color-text);
            background-image: url('/images/bg-library.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding-top: 4rem;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(193, 196, 199, 0.88);
            opacity: 45%;
            pointer-events: none;
            z-index: 0;
        }

        nav, main, .max-w-7xl {
            position: relative;
            z-index: 1;
        }

        /* Tout le contenu au-dessus du voile */
        nav, main, .max-w-7xl {
            position: relative;
            z-index: 1;
        }
        
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 40;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        
        body {
            padding-top: 4rem;
        }
        
        .nav-link {
            position: relative;
        
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
        }
        
        /* Logo */
        .logo {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.025em;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo-icon {
            width: 2rem;
            height: 2rem;
            background: var(--color-accent);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: white;
        }
        
        /* User Menu */
        .user-menu {
            position: relative;
        }
        
        .user-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            color: white;
        }
        
        .user-button:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            color: white;
        }
        
        .user-info {
            text-align: left;
        }
        
        .user-name {
            font-weight: 500;
            font-size: 0.875rem;
            color: white;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: capitalize;
        }
        
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: 12rem;
            background: var(--color-surface);
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            border: 1px solid var(--color-border);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-0.5rem);
            transition: all 0.2s ease;
            z-index: 50;
            overflow: hidden;
        }
        
        .dropdown-menu.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: block;
            width: 100%;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            color: var(--color-text);
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        
        .dropdown-item:hover {
            background: var(--color-surface-elevated);
        }
        
        .dropdown-divider {
            height: 1px;
            background: var(--color-border);
            margin: 0.25rem 0;
        }
        
        /* Flash Messages */
        .flash-message {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-0.5rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .flash-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        
        .flash-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        
        .flash-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }
        
        /* Main Content */
        .main-content {
            max-width: 80rem;
            margin: 0 auto;
            padding: 1.5rem 1rem;
        }
        
        @media (min-width: 640px) {
            .main-content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body class="min-h-screen">

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('books.index') }}" class="logo">
                    <span class="logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                        </svg>
                    </span>
                    <span>LibraFlow</span>
                </a>

                {{-- Menu central --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Tableau de bord
                    </a>

                    <a href="{{ route('books.index') }}" 
                       class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
                        Catalogue
                    </a>

                    @auth
                        @if (auth()->user()->isLecteur())
                            <a href="{{ route('lecteur.espace') }}" 
                               class="nav-link {{ request()->routeIs('lecteur.*') ? 'active' : '' }}">
                                Mon espace
                            </a>
                        @endif

                        @if (auth()->user()->isBibliothecaire() || auth()->user()->isAdmin())
                            <a href="{{ route('loans.index') }}" 
                               class="nav-link {{ request()->routeIs('loans.index') ? 'active' : '' }}">
                                Emprunts
                            </a>
                            <a href="{{ route('loans.retards') }}" 
                               class="nav-link {{ request()->routeIs('loans.retards') ? 'active' : '' }}">
                                Retards
                            </a>
                            <a href="{{ route('reservations.index') }}" 
                               class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                                Réservations
                            </a>
                            <a href="{{ route('books.create') }}" 
                               class="nav-link {{ request()->routeIs('books.create') ? 'active' : '' }}">
                                Ajouter
                            </a>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('settings.index') }}" 
                               class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                Paramètres
                            </a>
                        @endif
                    @endauth
                </div>

                {{-- Auth --}}
                <div class="flex items-center gap-3">
                    @auth
                        <div class="user-menu" style="position:relative;">
                            <button class="user-button" type="button" onclick="toggleDropdown()">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="user-info hidden sm:block">
                                    <div class="user-name">{{ auth()->user()->name }}</div>
                                    <div class="user-role">{{ auth()->user()->role }}</div>
                                </div>
                                <svg class="w-4 h-4 text-white/70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div id="dropdown" class="dropdown-menu">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    Mon profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color: var(--color-error);">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- MESSAGES FLASH --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-6">
        @if (session('success'))
            <div class="flash-message flash-success">
                <svg class="flash-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash-message flash-error">
                <svg class="flash-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <div>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    {{-- HEADER (optionnel - pour les pages avec en-tête personnalisé) --}}
    @if (isset($header))
        <div class="bg-white shadow-sm mb-6 sticky top-16 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $header }}
            </div>
        </div>
    @endif

    {{-- CONTENU --}}
    <main class="main-content">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.querySelector('.user-button');
            const dropdown = document.getElementById('dropdown');

            if (button && dropdown) {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = dropdown.classList.contains('open');
                    if (isOpen) {
                        dropdown.classList.remove('open');
                    } else {
                        dropdown.classList.add('open');
                    }
                });

                document.addEventListener('click', function () {
                    dropdown.classList.remove('open');
                });
            }
        });
    </script>
</body>

</html>