<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraFlow — @yield('title', 'Bibliothèque')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- NAVBAR --}}
    <nav class="bg-emerald-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('books.index') }}"
               class="text-xl font-bold tracking-wide">
                📚 LibraFlow
            </a>

            {{-- Menu central --}}
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('books.index') }}"
                   class="hover:text-emerald-200 transition">Catalogue</a>

                @auth
                    @if(auth()->user()->isLecteur())
                        <a href="{{ route('lecteur.espace') }}"
                           class="hover:text-emerald-200 transition">Mon espace</a>
                    @endif

                    @if(auth()->user()->isBibliothecaire() || auth()->user()->isAdmin())
                        <a href="{{ route('loans.index') }}"
                           class="hover:text-emerald-200 transition">Emprunts</a>
                        <a href="{{ route('loans.retards') }}"
                           class="hover:text-emerald-200 transition">Retards</a>
                        <a href="{{ route('reservations.index') }}"
                           class="hover:text-emerald-200 transition">Réservations</a>
                        <a href="{{ route('books.create') }}"
                           class="hover:text-emerald-200 transition">+ Livre</a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('settings.index') }}"
                           class="hover:text-emerald-200 transition">Paramètres</a>
                    @endif
                @endauth
            </div>

            {{-- Auth --}}
            <div class="flex items-center gap-4 text-sm">
                @auth
                    <span class="text-emerald-300">
                        {{ auth()->user()->name }}
                        <span class="text-xs bg-emerald-700 px-2 py-0.5 rounded-full ml-1">
                            {{ auth()->user()->role }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="hover:text-emerald-200 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="hover:text-emerald-200 transition">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- MESSAGES FLASH --}}
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800
                        rounded-lg px-4 py-3 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800
                        rounded-lg px-4 py-3">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- CONTENU --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
