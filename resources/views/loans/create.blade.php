@extends('layouts.app')
@section('title', 'Nouvel emprunt')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Enregistrer un emprunt</h1>

    <form method="POST" action="{{ route('loans.store') }}"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Livre *</label>
            <select name="book_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Sélectionner un livre disponible</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}"
                        {{ request('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} ({{ $book->available_copies }} dispo)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lecteur *</label>
            <select name="user_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Sélectionner un lecteur</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-emerald-700 text-white px-6 py-2 rounded-lg
                           hover:bg-emerald-800 transition text-sm">
                Enregistrer l'emprunt
            </button>
            <a href="{{ route('loans.index') }}"
               class="border border-gray-300 px-6 py-2 rounded-lg text-sm
                      hover:bg-gray-50 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
