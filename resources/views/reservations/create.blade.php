@extends('layouts.app')
@section('title', 'Créer une réservation')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour au catalogue
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Nouvelle réservation</h1>
        <p class="text-gray-600 mb-6">Réservez un livre qui n'est pas disponible actuellement</p>

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

        <form method="POST" action="{{ route('reservations.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="book_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Sélectionner un livre
                </label>
                <select name="book_id" id="book_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">-- Choisir un livre --</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
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
                <p class="text-xs text-gray-500 mt-2">💡 Vous pouvez réserver un livre qui n'est pas disponible</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">À propos des réservations</p>
                        <p>Vous serez mis en file d'attente si vous êtes le premier à réserver ce livre. Une notification vous sera envoyée dès qu'il sera disponible.</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Réserver
                </button>
                <a href="{{ route('books.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
