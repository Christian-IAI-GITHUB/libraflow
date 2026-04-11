@extends('layouts.app')
@section('title', 'Ajouter un livre')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un livre</h1>

    <form method="POST" action="{{ route('books.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Auteur *</label>
                <input type="text" name="author" value="{{ old('author') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                <input type="text" name="category" value="{{ old('category') }}"
                       list="categories-list"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                       required>
                <datalist id="categories-list">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre d'exemplaires *
                </label>
                <input type="number" name="total_copies"
                       value="{{ old('total_copies', 1) }}" min="1"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400"
                       required>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Image de couverture
                </label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full text-sm text-gray-500">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-emerald-700 text-white px-6 py-2 rounded-lg
                           hover:bg-emerald-800 transition text-sm">
                Ajouter le livre
            </button>
            <a href="{{ route('books.index') }}"
               class="border border-gray-300 px-6 py-2 rounded-lg text-sm
                      hover:bg-gray-50 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
