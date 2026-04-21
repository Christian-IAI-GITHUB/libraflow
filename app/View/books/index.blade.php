@extends('layouts.app')
@section('title', 'Catalogue')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Catalogue des livres</h1>
    @can('create', App\Models\Book::class)
        <a href="{{ route('books.create') }}"
           class="bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition text-sm">
            + Ajouter un livre
        </a>
    @endcan
</div>

<form method="GET" action="{{ route('books.index') }}"
      class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Titre, auteur, catégorie..."
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-emerald-400">

        <select name="category"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>

        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
            <input type="checkbox" name="disponible" value="1"
                   {{ request('disponible') ? 'checked' : '' }}
                   class="accent-emerald-600">
            Disponibles uniquement
        </label>

        <button type="submit"
                class="bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-800 transition">
            Rechercher
        </button>

        @if(request()->hasAny(['search', 'category', 'disponible']))
            <a href="{{ route('books.index') }}" class="text-sm text-gray-400 hover:text-gray-700 self-center">
                Effacer
            </a>
        @endif
    </div>
</form>

@if($books->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">📭</p>
        <p class="text-lg">Aucun livre trouvé.</p>
        @if(request()->hasAny(['search', 'category', 'disponible']))
            <a href="{{ route('books.index') }}" class="text-sm text-emerald-600 hover:underline mt-2 inline-block">
                Voir tout le catalogue
            </a>
        @endif
    </div>
@else
    <p class="text-sm text-gray-400 mb-4">{{ $books->total() }} livre(s)</p>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($books as $book)
            <a href="{{ route('books.show', $book) }}"
               class="block bg-white rounded-xl shadow-sm border border-gray-200
                      hover:shadow-md transition overflow-hidden group">
                <div class="h-44 bg-emerald-50 flex items-center justify-center overflow-hidden">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}"
                             alt="{{ $book->title }}"
                             class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <span class="text-5xl">📖</span>
                    @endif
                </div>
                <div class="p-3">
                    <p class="font-semibold text-gray-800 text-sm leading-tight line-clamp-2 mb-1">
                        {{ $book->title }}
                    </p>
                    <p class="text-xs text-gray-500 mb-2">{{ $book->author }}</p>
                    @if($book->available_copies > 0)
                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
                            {{ $book->available_copies }}/{{ $book->total_copies }} dispo
                        </span>
                    @else
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">
                            Indisponible
                        </span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-6">{{ $books->withQueryString()->links() }}</div>
@endif

@endsection
