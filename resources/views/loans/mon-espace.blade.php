@extends('layouts.app')
@section('title', 'Mon espace')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">
    Mon espace — {{ auth()->user()->name }}
</h1>

{{-- Pénalités (affichage bienveillant, réponse à la question UX du prof) --}}
@if($totalPenalites > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex
                items-start gap-3">
        <span class="text-2xl">📋</span>
        <div>
            <p class="font-semibold text-amber-800">
                Des frais sont associés à votre compte
            </p>
            <p class="text-sm text-amber-700 mt-1">
                Un total de
                <strong>{{ number_format($totalPenalites, 0, ',', ' ') }} FCFA</strong>
                correspond à des livres rendus en retard.
                Merci de vous rapprocher de la bibliothèque pour régulariser.
            </p>
        </div>
    </div>
@endif

{{-- Emprunts en cours --}}
<div class="mb-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-3">
        Mes emprunts en cours ({{ $empruntsEnCours->count() }})
    </h2>
    @forelse($empruntsEnCours as $loan)
        @php $enRetard = $loan->due_at->isPast(); @endphp
        <div class="bg-white rounded-xl border {{ $enRetard ? 'border-red-200' : 'border-gray-200' }}
                    p-4 mb-3 flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-800">{{ $loan->book->title }}</p>
                <p class="text-xs text-gray-500">{{ $loan->book->author }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm {{ $enRetard ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                    À rendre le {{ $loan->due_at->format('d/m/Y') }}
                </p>
                @if($enRetard)
                    <p class="text-xs text-red-500 mt-1">
                        {{ now()->diffInDays($loan->due_at) }} jours de retard
                    </p>
                @else
                    <p class="text-xs text-emerald-600 mt-1">
                        Dans {{ now()->diffInDays($loan->due_at) }} jours
                    </p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-400 text-sm">Aucun emprunt en cours.</p>
    @endforelse
</div>

{{-- Réservations en attente --}}
@if($reservations->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">
            Mes réservations ({{ $reservations->count() }})
        </h2>
        @foreach($reservations as $reservation)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-3
                        flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-800">
                        {{ $reservation->book->title }}
                    </p>
                    <p class="text-xs text-amber-600 mt-1">
                        ⏳ En attente depuis le
                        {{ $reservation->reserved_at->format('d/m/Y') }}
                    </p>
                </div>
                <form method="POST"
                      action="{{ route('reservations.destroy', $reservation) }}">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-500 hover:underline">
                        Annuler
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif

{{-- Historique --}}
<div>
    <h2 class="text-lg font-semibold text-gray-700 mb-3">Historique</h2>
    @forelse($historique as $loan)
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-2
                    flex items-center justify-between">
            <div>
                <p class="font-medium text-gray-800">{{ $loan->book->title }}</p>
                <p class="text-xs text-gray-400">
                    Rendu le {{ $loan->returned_at->format('d/m/Y') }}
                </p>
            </div>
            @if($loan->penalty_amount > 0)
                <span class="text-xs text-red-500">
                    {{ number_format($loan->penalty_amount, 0, ',', ' ') }} FCFA
                </span>
            @else
                <span class="text-xs text-emerald-600">✓ À temps</span>
            @endif
        </div>
    @empty
        <p class="text-gray-400 text-sm">Aucun historique.</p>
    @endforelse
    <div class="mt-4">{{ $historique->links() }}</div>
</div>

@endsection
