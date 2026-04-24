@extends('layouts.app')
@section('title', 'Modifier une réservation')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour aux réservations
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier la réservation</h1>
            <p class="text-gray-600">
                Livre : <span class="font-semibold">{{ $reservation->book->title }}</span>
                <br/>
                Lecteur : <span class="font-semibold">{{ $reservation->user->name }}</span>
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-sm font-semibold text-red-800 mb-2">Erreur</h3>
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('reservations.update', $reservation) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="book_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Livre
                </label>
                <select name="book_id" id="book_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">-- Choisir un livre --</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id', $reservation->book_id) == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} ({{ $book->author }})
                            @if ($book->available_copies <= 0)
                                - Indisponible
                            @else
                                - {{ $book->available_copies }}/{{ $book->total_copies }} disponible(s)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Statut
                </label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="en_attente" {{ old('status', $reservation->status) === 'en_attente' ? 'selected' : '' }}>
                        En attente
                    </option>
                    <option value="confirmee" {{ old('status', $reservation->status) === 'confirmee' ? 'selected' : '' }}>
                        Confirmée
                    </option>
                    <option value="annulee" {{ old('status', $reservation->status) === 'annulee' ? 'selected' : '' }}>
                        Annulée
                    </option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="text-sm text-gray-700 space-y-2">
                    <p><strong>Réservée le :</strong> {{ $reservation->reserved_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Statut actuel :</strong> <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if($reservation->status === 'en_attente')
                            bg-amber-100 text-amber-800
                        @elseif($reservation->status === 'confirmee')
                            bg-green-100 text-green-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                    </span></p>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Mettre à jour
                </button>
                <a href="{{ route('reservations.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    Annuler
                </a>
            </div>
        </form>

        <!-- Section suppression -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Zone de danger</h3>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-700 mb-4">
                    Une fois supprimée, cette réservation ne pourra pas être récupérée.
                </p>
                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Supprimer la réservation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
