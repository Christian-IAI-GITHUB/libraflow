@extends('layouts.app')
@section('title', 'Emprunts en cours')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Emprunts en cours</h1>
    <a href="{{ route('loans.create') }}"
       class="bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm
              hover:bg-emerald-800 transition">
        + Nouvel emprunt
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Livre</th>
                <th class="px-4 py-3 text-left">Lecteur</th>
                <th class="px-4 py-3 text-left">Emprunté le</th>
                <th class="px-4 py-3 text-left">À rendre le</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($loans as $loan)
                @php $enRetard = $loan->due_at->isPast(); @endphp
                <tr class="{{ $enRetard ? 'bg-red-50' : '' }}">
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $loan->book->title }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $loan->user->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $loan->borrowed_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 {{ $enRetard ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ $loan->due_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($enRetard)
                            <span class="bg-red-100 text-red-700 text-xs
                                         px-2 py-1 rounded-full">
                                En retard
                            </span>
                        @else
                            <span class="bg-emerald-100 text-emerald-700 text-xs
                                         px-2 py-1 rounded-full">
                                En cours
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST"
                              action="{{ route('loans.retour', $loan) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-blue-600 text-white px-3 py-1
                                          rounded hover:bg-blue-700 transition">
                                Retour
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-400">
                        Aucun emprunt en cours.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $loans->links() }}</div>
@endsection
