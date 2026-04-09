@extends('layouts.app')
@section('title', 'Paramètres')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Paramètres de la bibliothèque</h1>

    <div class="space-y-4">

        {{-- Durée d'emprunt --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-700 mb-1">Durée d'emprunt</h2>
            <p class="text-xs text-gray-400 mb-3">
                Nombre de jours avant la date de retour prévue.
            </p>
            <form method="POST"
                  action="{{ route('settings.update', 'duree_emprunt') }}"
                  class="flex items-center gap-3">
                @csrf @method('PUT')
                <input type="number" name="value" min="1" max="365"
                       value="{{ $settings['duree_emprunt'] }}"
                       class="w-24 border border-gray-300 rounded-lg px-3 py-2
                              text-sm focus:outline-none focus:ring-2
                              focus:ring-emerald-400">
                <span class="text-sm text-gray-500">jours</span>
                <button type="submit"
                        class="bg-emerald-700 text-white px-4 py-2 rounded-lg
                               text-sm hover:bg-emerald-800 transition">
                    Enregistrer
                </button>
            </form>
        </div>

        {{-- Pénalité journalière --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-700 mb-1">Pénalité journalière</h2>
            <p class="text-xs text-gray-400 mb-3">
                Montant appliqué chaque jour de retard.
            </p>
            <form method="POST"
                  action="{{ route('settings.update', 'penalite_journaliere') }}"
                  class="flex items-center gap-3">
                @csrf @method('PUT')
                <input type="number" name="value" min="0"
                       value="{{ $settings['penalite_journaliere'] }}"
                       class="w-28 border border-gray-300 rounded-lg px-3 py-2
                              text-sm focus:outline-none focus:ring-2
                              focus:ring-emerald-400">
                <span class="text-sm text-gray-500">FCFA / jour</span>
                <button type="submit"
                        class="bg-emerald-700 text-white px-4 py-2 rounded-lg
                               text-sm hover:bg-emerald-800 transition">
                    Enregistrer
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
