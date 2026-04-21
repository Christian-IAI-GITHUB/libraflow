@extends('layouts.app')
@section('title', 'Retards')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Emprunts en retard
        @if($loans->count() > 0)
            <span class="text-base font-normal text-red-500 ml-1">({{ $loans->count() }})</span>
        @endif
    </h1>
    <a href="{{ route('loans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
        ← Tous les emprunts
    </a>
</div>

@if($loans->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">🎉</p>
        <p class="text-lg font-medium">Aucun retard en ce moment !</p>
        <p class="text-sm mt-1">Tous les livres sont rendus dans les délais.</p>
    </div>
@else
    @php $totalPenalites = $loans->sum('penalite_actuelle'); @endphp

    {{-- Résumé --}}
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-4">
        <span class="text-3xl">⚠️</span>
        <div>
            <p class="font-semibold text-red-800">{{ $loans->count() }} emprunt(s) en retard</p>
            <p class="text-sm text-red-600">
                Pénalités cumulées estimées :
                <strong>{{ number_format($totalPenalites, 0, ',', ' ') }} FCFA</strong>
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-red-50 text-red-700 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Livre</th>
                    <th class="px-4 py-3 text-left">Lecteur</th>
                    <th class="px-4 py-3 text-left">Devait rendre le</th>
                    <th class="px-4 py-3 text-left">Jours de retard</th>
                    <th class="px-4 py-3 text-left">Pénalité estimée</th>
                    <th class="px-4 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($loans as $loan)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <a href="{{ route('books.show', $loan->book) }}"
                               class="hover:text-emerald-700 hover:underline">
                                {{ $loan->book->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $loan->user->name }}</td>
                        <td class="px-4 py-3 text-red-600 font-semibold">
                            {{ $loan->due_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ (int) $loan->due_at->diffInDays(now()) }} jours
                        </td>
                        <td class="px-4 py-3 font-semibold text-red-700">
                            {{ number_format($loan->penalite_actuelle, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('loans.retour', $loan) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-blue-600 text-white px-3 py-1 rounded
                                              hover:bg-blue-700 transition">
                                    Enregistrer le retour
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
