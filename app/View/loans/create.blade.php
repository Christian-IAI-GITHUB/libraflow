@extends('layouts.app')
@section('title', 'Nouvel emprunt')

@section('content')

<div class="max-w-lg">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Enregistrer un emprunt</h1>
        <a href="{{ route('loans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Retour aux emprunts
        </a>
    </div>

    <form method="POST" action="{{ route('loans.store') }}"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Livre <span class="text-red-500">*</span>
            </label>
            <select name="book_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-400
                           @error('book_id') border-red-400 @enderror">
                <option value="">Sélectionner un livre disponible</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}"
                        {{ request('book_id') == $book->id || old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} — {{ $book->author }}
                        ({{ $book->available_copies }} dispo)
                    </option>
                @endforeach
            </select>
            @error('book_id')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Lecteur <span class="text-red-500">*</span>
            </label>
            <select name="user_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-400
                           @error('user_id') border-red-400 @enderror">
                <option value="">Sélectionner un lecteur</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} — {{ $user->email }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        @php $duree = \App\Models\Setting::getValue('duree_emprunt', 14); @endphp
        <p class="text-xs text-gray-400">
            Durée d'emprunt configurée : <strong>{{ $duree }} jours</strong>.
            La date de retour sera calculée automatiquement.
        </p>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-emerald-700 text-white px-6 py-2 rounded-lg
                           hover:bg-emerald-800 transition text-sm">
                Enregistrer l'emprunt
            </button>
            <a href="{{ route('loans.index') }}"
               class="border border-gray-300 px-6 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@endsection
