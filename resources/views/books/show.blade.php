@extends('layouts.app')
@section('title', $book->title)

@section('content')

    <div class="max-w-3xl mx-auto">
        <a href="{{ route('books.index') }}" class="text-sm text-emerald-700 hover:underline mb-4 inline-block">
            ← Retour au catalogue
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex gap-6">

            {{-- Couverture --}}
            <div
                class="w-36 h-48 bg-emerald-50 rounded-lg flex items-center
                    justify-center overflow-hidden flex-shrink-0">
                @if ($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                        class="block h-full w-full object-cover object-center">
                @else
                    <span class="text-6xl">📖</span>
                @endif
            </div>

            {{-- Détails --}}
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $book->title }}</h1>
                <p class="text-gray-500 mb-1">par <strong>{{ $book->author }}</strong></p>
                <p class="text-sm text-gray-400 mb-4">
                    Catégorie : {{ $book->category }}
                    @if ($book->isbn)
                        · ISBN : {{ $book->isbn }}
                    @endif
                </p>

                {{-- Disponibilité claire (réponse à la question UX du prof) --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm font-semibold text-gray-600 mb-2">
                        Disponibilité
                    </p>
                    <div class="flex gap-1 mb-2">
                        @for ($i = 0; $i < $book->total_copies; $i++)
                            <div
                                class="w-8 h-8 rounded-md flex items-center
                                    justify-center text-xs font-bold
                            {{ $i < $book->available_copies ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-400' }}">
                                {{ $i < $book->available_copies ? '✓' : '✗' }}
                            </div>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-600">
                        @if ($book->available_copies > 0)
                            <span class="text-emerald-700 font-semibold">
                                {{ $book->available_copies }} exemplaire(s) disponible(s)
                            </span>
                            sur {{ $book->total_copies }} au total.
                        @else
                            <span class="text-red-600 font-semibold">
                                Aucun exemplaire disponible
                            </span>
                            ({{ $book->total_copies }} exemplaire(s) tous empruntés).
                        @endif
                    </p>
                </div>

                {{-- Actions selon rôle --}}
                @auth
                    @if ($book->available_copies > 0)
                        @can('create', App\Models\Loan::class)
                            <a href="{{ route('loans.create') }}?book_id={{ $book->id }}"
                                class="bg-emerald-700 text-white px-5 py-2 rounded-lg
                                  hover:bg-emerald-800 transition text-sm inline-block">
                                Enregistrer un emprunt
                            </a>
                        @endcan
                    @else
                        {{-- Réservation pour le lecteur --}}
                        @if (auth()->user()->isLecteur())
                            @if ($maReservation)
                                <div
                                    class="bg-amber-50 border border-amber-200
                                        rounded-lg p-3 text-sm text-amber-800">
                                    ⏳ Vous êtes dans la file d'attente
                                    ({{ $positionFile }} personne(s) avant vous).
                                    <form method="POST"
                                        action="{{ route('reservations.destroy', $maReservation) }}"
                                        class="inline ml-2">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:underline text-xs">
                                            Annuler ma réservation
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mb-3 text-sm text-gray-500">
                                    {{ $positionFile }} personne(s) déjà en attente.
                                </div>
                                <form method="POST" action="{{ route('reservations.store') }}">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit"
                                        class="bg-amber-500 text-white px-5 py-2
                                               rounded-lg hover:bg-amber-600
                                               transition text-sm">
                                        Réserver ce livre
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                @endauth

                {{-- Actions admin/biblio --}}
                @can('update', $book)
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('books.edit', $book) }}"
                            class="text-sm border border-gray-300 px-4 py-2 rounded-lg
                              hover:bg-gray-50 transition">
                            Modifier
                        </a>
                        @can('delete', $book)
                            <form method="POST" action="{{ route('books.destroy', $book) }}"
                                onsubmit="return confirm('Supprimer ce livre ?')">
                                @csrf @method('DELETE')
                                <button
                                    class="text-sm border border-red-200 text-red-600
                                          px-4 py-2 rounded-lg hover:bg-red-50 transition">
                                    Supprimer
                                </button>
                            </form>
                        @endcan
                    </div>
                @endcan
            </div>
        </div>
    </div>

@endsection
