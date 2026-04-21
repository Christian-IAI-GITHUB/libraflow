@extends('layouts.app')
@section('title', 'Réservations')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        File de réservations
        @if($reservations->total() > 0)
            <span class="text-base font-normal text-amber-500 ml-1">({{ $reservations->total() }})</span>
        @endif
    </h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
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
                    <td class="px-4 py-3 font-medium text-gray-800">
                        <a href="{{ route('books.show', $reservation->book) }}"
                           class="hover:text-emerald-700 hover:underline">
                            {{ $reservation->book->title }}
                        </a>
                        <p class="text-xs text-gray-400">{{ $reservation->book->author }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $reservation->user->name }}
                        <p class="text-xs text-gray-400">{{ $reservation->user->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $reservation->reserved_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @if($reservation->status === 'notifie')
                            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                                Notifié
                            </span>
                        @else
                            <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">
                                En attente
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('reservations.destroy', $reservation) }}">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:underline">
                                Annuler
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        <p class="text-3xl mb-2">✅</p>
                        Aucune réservation en attente.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $reservations->links() }}</div>

@endsection
