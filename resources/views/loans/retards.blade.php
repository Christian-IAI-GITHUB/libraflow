@extends('layouts.app')
@section('title', 'Retards')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Emprunts en retard
    <span class="text-base font-normal text-red-500">({{ $loans->count() }})</span>
</h1>

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
            @forelse($loans as $loan)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $loan->book->title }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $loan->user->name }}</td>
                    <td class="px-4 py-3 text-red-600 font-semibold">
                        {{ $loan->due_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        {{ now()->diffInDays($loan->due_at) }} jours
                    </td>
                    <td class="px-4 py-3 font-semibold text-red-700">
                        {{ number_format($loan->penalite_actuelle, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('loans.retour', $loan) }}">
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
                        🎉 Aucun retard en ce moment.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
