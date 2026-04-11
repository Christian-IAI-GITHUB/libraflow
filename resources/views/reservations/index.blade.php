@extends('layouts.app')
@section('title', 'Réservations')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">File de réservations</h1>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Livre</th>
                <th class="px-4 py-3 text-left">Lecteur</th>
                <th class="px-4 py-3 text-left">Réservé le</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reservations as $reservation)
                <tr>
                    <td class="px-4 py-3 font-medium">
                        {{ $reservation->book->title }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $reservation->user->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $reservation->reserved_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-amber-100 text-amber-700 text-xs
                                     px-2 py-1 rounded-full">
                            En attente
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST"
                              action="{{ route('reservations.destroy', $reservation) }}">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:underline">
                                Annuler
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-400">
                        Aucune réservation en attente.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $reservations->links() }}</div>
@endsection
