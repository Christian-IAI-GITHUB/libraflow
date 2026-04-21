@extends('layouts.app')
@section('title', 'Ajouter un livre')

@section('content')

<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ajouter un livre</h1>
        <a href="{{ route('books.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Retour au catalogue
        </a>
    </div>

    <form method="POST" action="{{ route('books.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required autofocus
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400
                              @error('title') border-red-400 @enderror">
                @error('title')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Auteur <span class="text-red-500">*</span></label>
                <input type="text" name="author" value="{{ old('author') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400
                              @error('author') border-red-400 @enderror">
                @error('author')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400
                              @error('isbn') border-red-400 @enderror">
                @error('isbn')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
                <input type="text" name="category" value="{{ old('category') }}"
                       list="categories-list" required
                       placeholder="Ex : Roman, Science, Histoire..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400
                              @error('category') border-red-400 @enderror">
                <datalist id="categories-list">
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
                @error('category')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre d'exemplaires <span class="text-red-500">*</span></label>
                <input type="number" name="total_copies" value="{{ old('total_copies', 1) }}" min="1" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400
                              @error('total_copies') border-red-400 @enderror">
                @error('total_copies')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Image de couverture</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3
                              file:rounded-lg file:border-0 file:text-sm file:bg-emerald-50
                              file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG, max 2 Mo</p>
                @error('cover_image')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="bg-emerald-700 text-white px-6 py-2 rounded-lg
                           hover:bg-emerald-800 transition text-sm">
                Ajouter le livre
            </button>
            <a href="{{ route('books.index') }}"
               class="border border-gray-300 px-6 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@endsection
