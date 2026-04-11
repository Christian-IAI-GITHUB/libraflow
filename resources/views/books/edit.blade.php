@extends('layouts.app')
@section('title', 'Modifier ' . $book->title)

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier le livre</h1>

        <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Auteur *</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                    <input type="text" name="category" value="{{ old('category', $book->category) }}"
                        list="categories-list"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required>
                    <datalist id="categories-list">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre d'exemplaires *
                    </label>
                    <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}"
                        min="1"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                        required>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nouvelle couverture (laisser vide pour garder l'actuelle)
                    </label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-500">

                    @if ($book->cover_image)
                        <label class="mt-3 flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remove_cover_image" value="1" class="accent-emerald-600">
                            Supprimer la couverture actuelle
                        </label>
                    @endif
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-emerald-700 text-white px-6 py-2 rounded-lg
                           hover:bg-emerald-800 transition text-sm">
                    Enregistrer
                </button>
                <a href="{{ route('books.show', $book) }}"
                    class="border border-gray-300 px-6 py-2 rounded-lg text-sm
                      hover:bg-gray-50 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
