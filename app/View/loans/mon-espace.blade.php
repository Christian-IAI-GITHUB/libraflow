@extends('layouts.app')
@section('title', 'Mon espace')

@section('content')

<h1 class="text-2xl font-bold text-gray-800 mb-1">Mon espace</h1>
<p class="text-sm text-gray-500 mb-6">{{ auth()->user()->name }} · {{ auth()->user()->email }}</p>

{{-- Alerte pénalités --}}
@if($totalPenalites > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-start gap-3">
        <span class="text-2xl">📋</span>
        <div>
            <p class="font-semibold text-amber-800">Des frais sont associés à votre compte</p>
            <p class="text-sm text-amber-700 mt-1">
                Un total de <strong>{{ number_format($totalPenalites, 0, ',', ' ') }} FCFA</strong>
                correspond à des livres rendus en retard.
                Veuillez vous rapprocher de la bibliothèque pour régulariser.
            </p>
        </div>
    </div>
@endif

{{-- Emprunts en cours --}}
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-3">
        Emprunts en cours
        <span class="text-sm font-normal text-gray-400">({{ $empruntsEnCours->count() }})</span>
    </h2>

    @forelse($empruntsEnCours as $loan)
        @php $enRetard = $loan->due_at->isPast(); @endphp
        <div class="bg-white rounded-xl border {{ $enRetard ? 'border-red-200' : 'border-gray-200' }}
                    shadow-sm p-4 mb-3 flex items-center justify-between">
            <div>
                <a href="{{ route('books.show', $loan->book) }}"
                   class="font-medium text-gray-800 hover:text-emerald-700 hover:underline">
                    {{ $loan->book->title }}
                </a>
                <p class="text-xs text-gray-400 mt-0.5">{{ $loan->book->author }}</p>
            </div>
            <div class="text-right flex-shrink-0 ml-4">
                <p class="text-sm {{ $enRetard ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                    À rendre le {{ $loan->due_at->format('d/m/Y') }}
                </p>
                @if($enRetard)
                    <p class="text-xs text-red-400 mt-0.5">
                        {{ (int) $loan->due_at->diffInDays(now()) }} jours de retard
                    </p>
                @else
                    <p class="text-xs text-emerald-600 mt-0.5">
                        Dans {{ (int) now()->diffInDays($loan->due_at) }} jour(s)
                    </p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400 bg-white border border-gray-200 rounded-xl p-4">
            Vous n'avez aucun emprunt en cours.
            <a href="{{ route('books.index') }}" class="text-emerald-600 hover:underline ml-1">
                Parcourir le catalogue →
            </a>
        </p>
    @endforelse
</div>

{{-- Réservations en attente --}}
@if($reservations->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">
            Mes réservations
            <span class="text-sm font-normal text-gray-400">({{ $reservations->count() }})</span>
        </h2>

        @foreach($reservations as $reservation)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-3
                        flex items-center justify-between">
                <div>
                    <a href="{{ route('books.show', $reservation->book) }}"
                       class="font-medium text-gray-800 hover:text-emerald-700 hover:underline">
                        {{ $reservation->book->title }}
                    </a>
                    <p class="text-xs text-amber-600 mt-0.5">
                        ⏳ En attente depuis le {{ $reservation->reserved_at->format('d/m/Y') }}
                    </p>
                </div>
                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:underline flex-shrink-0 ml-4">
                        Annuler
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif

{{-- Historique --}}
<div>
    <h2 class="text-lg font-semibold text-gray-700 mb-3">Historique des emprunts</h2>

    @forelse($historique as $loan)
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-2
                    flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-800">{{ $loan->book->title }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    Rendu le {{ $loan->returned_at->format('d/m/Y') }}
                </p>
            </div>
            <div class="flex-shrink-0 ml-4">
                @if($loan->penalty_amount > 0)
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">
                        {{ number_format($loan->penalty_amount, 0, ',', ' ') }} FCFA
                    </span>
                @else
                    <span class="text-xs bg-emerald-100 text-emerald-600 px-2 py-1 rounded-full">
                        ✓ À temps
                    </span>
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400">Aucun historique d'emprunt.</p>
    @endforelse

    <div class="mt-4">{{ $historique->links() }}</div>
</div>

@endsection
