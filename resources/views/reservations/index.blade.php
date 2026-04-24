@extends('layouts.app')
@section('title', 'Réservations')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">File de réservations</h1>
    @auth
        @if (auth()->user()->isLecteur())
            <a href="{{ route('reservations.create') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle réservation
            </a>
        @endif
    @endauth
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Livre</th>
                <th class="px-4 py-3 text-left">Lecteur</th>
                <th class="px-4 py-3 text-left">Réservé le</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reservations as $reservation)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $reservation->book->title }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $reservation->user->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $reservation->reserved_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @if ($reservation->status === 'en_attente')
                            <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">
                                En attente
                            </span>
                        @elseif ($reservation->status === 'confirmee')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                                Confirmée
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                Annulée
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            @can('update', $reservation)
                                <button>
                                    <a href="{{ route('reservations.edit', $reservation) }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                        Modifier
                                    </a>
                                </button>
                            @endcan
                            @can('delete', $reservation)
                                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 hover:underline">
                                        Supprimer
                                    </button>
                                </form>
                            @endcan
                        </div>
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
