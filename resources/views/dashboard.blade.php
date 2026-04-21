@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')

{{-- ════════════════════════════════════════════ --}}
{{-- VUE ADMIN / BIBLIOTHÉCAIRE                  --}}
{{-- ════════════════════════════════════════════ --}}
@if(auth()->user()->isAdmin() || auth()->user()->isBibliothecaire())

@php
    $totalLivres     = \App\Models\Book::count();
    $livresDispo     = \App\Models\Book::where('available_copies', '>', 0)->count();
    $empruntsEnCours = \App\Models\Loan::whereNull('returned_at')->count();
    $empruntsRetard  = \App\Models\Loan::whereNull('returned_at')->where('due_at', '<', now())->count();
    $reservations    = \App\Models\Reservation::where('status', 'en_attente')->count();
@endphp

{{-- En-tête --}}
<div style="margin-bottom:28px;">
    <h1 style="font-size:1.6rem; font-weight:700; color:#0f172a; margin:0 0 4px;">
         {{ auth()->user()->name }} 
    </h1>
    <p style="color:#64748b; font-size:0.9rem; margin:0;">
        Voici un aperçu de la bibliothèque pour aujourd'hui,
        {{ now()->translatedFormat('l d F Y') }}.
    </p>
</div>

{{-- KPI Cards --}}
<div style="display:grid; grid-template-columns:repeat(5,1fr); gap:16px; margin-bottom:32px;">

    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid #2563eb;">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">
            Catalogue
        </div>
        <div style="font-size:2rem; font-weight:700; color:#0f172a; line-height:1;">{{ $totalLivres }}</div>
        <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">livres enregistrés</div>
    </div>

    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid #10b981;">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">
            Disponibles
        </div>
        <div style="font-size:2rem; font-weight:700; color:#0f172a; line-height:1;">{{ $livresDispo }}</div>
        <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">sur {{ $totalLivres }} titres</div>
    </div>

    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid #6366f1;">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">
            Emprunts
        </div>
        <div style="font-size:2rem; font-weight:700; color:#0f172a; line-height:1;">{{ $empruntsEnCours }}</div>
        <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">en cours actuellement</div>
    </div>

    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid {{ $empruntsRetard > 0 ? '#ef4444' : '#e2e8f0' }};">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">
            Retards
        </div>
        <div style="font-size:2rem; font-weight:700; color:{{ $empruntsRetard > 0 ? '#ef4444' : '#94a3b8' }}; line-height:1;">
            {{ $empruntsRetard }}
        </div>
        <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">
            {{ $empruntsRetard > 0 ? 'action requise' : 'aucun retard' }}
        </div>
    </div>

    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid #f59e0b;">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">
            Réservations
        </div>
        <div style="font-size:2rem; font-weight:700; color:#0f172a; line-height:1;">{{ $reservations }}</div>
        <div style="font-size:0.78rem; color:#64748b; margin-top:4px;">en file d'attente</div>
    </div>

</div>

{{-- Grille principale --}}
<div style="display:grid; grid-template-columns:1fr 320px; gap:20px;">

    {{-- Emprunts en retard --}}
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:0.95rem; font-weight:600; color:#0f172a;">Emprunts en retard</div>
                <div style="font-size:0.78rem; color:#94a3b8; margin-top:1px;">Nécessitent une attention immédiate</div>
            </div>
            <a href="{{ route('loans.retards') }}"
               style="font-size:0.78rem; color:#2563eb; text-decoration:none; font-weight:500;">
                Voir tout →
            </a>
        </div>

        @php
            $retards = \App\Models\Loan::with(['book','user'])
                ->whereNull('returned_at')
                ->where('due_at', '<', now())
                ->orderBy('due_at')
                ->limit(6)->get();
        @endphp

        @if($retards->isEmpty())
            <div style="padding:40px; text-align:center; color:#94a3b8;">
                <div style="font-size:2rem; margin-bottom:8px;">✓</div>
                <div style="font-size:0.875rem;">Aucun retard en ce moment</div>
            </div>
        @else
            <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                <thead>
                    <tr style="background:#fafafa;">
                        <th style="padding:10px 20px; text-align:left; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Livre</th>
                        <th style="padding:10px 20px; text-align:left; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Lecteur</th>
                        <th style="padding:10px 20px; text-align:left; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Dû le</th>
                        <th style="padding:10px 20px; text-align:left; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">Retard</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retards as $loan)
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:12px 20px; color:#0f172a; font-weight:500;">
                                {{ Str::limit($loan->book->title, 28) }}
                            </td>
                            <td style="padding:12px 20px; color:#475569;">{{ $loan->user->name }}</td>
                            <td style="padding:12px 20px; color:#ef4444; font-weight:600;">
                                {{ $loan->due_at->format('d/m/Y') }}
                            </td>
                            <td style="padding:12px 20px;">
                                <span style="background:#fef2f2; color:#ef4444; font-size:0.72rem; font-weight:600; padding:3px 8px; border-radius:20px;">
                                    {{ (int)$loan->due_at->diffInDays(now()) }}j
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Actions rapides --}}
    <div style="display:flex; flex-direction:column; gap:16px;">

        {{-- Actions --}}
        <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
            <div style="padding:14px 18px; border-bottom:1px solid #f1f5f9;">
                <div style="font-size:0.9rem; font-weight:600; color:#0f172a;">Actions rapides</div>
            </div>
            <div style="padding:12px;">
                <a href="{{ route('loans.create') }}"
                   style="display:flex; align-items:center; gap:12px; padding:11px 12px; border-radius:8px; background:#2563eb; color:#fff; text-decoration:none; margin-bottom:8px; font-size:0.875rem; font-weight:500;">
                     Nouvel emprunt
                </a>
                <a href="{{ route('books.create') }}"
                   style="display:flex; align-items:center; gap:12px; padding:11px 12px; border-radius:8px; background:#f8fafc; color:#374151; text-decoration:none; margin-bottom:8px; font-size:0.875rem; font-weight:500; border:1px solid #e2e8f0;">
                     Ajouter un livre
                </a>
                <a href="{{ route('loans.index') }}"
                   style="display:flex; align-items:center; gap:12px; padding:11px 12px; border-radius:8px; background:#f8fafc; color:#374151; text-decoration:none; margin-bottom:8px; font-size:0.875rem; font-weight:500; border:1px solid #e2e8f0;">
                     Voir les emprunts
                </a>
                <a href="{{ route('reservations.index') }}"
                   style="display:flex; align-items:center; gap:12px; padding:11px 12px; border-radius:8px; background:#f8fafc; color:#374151; text-decoration:none; font-size:0.875rem; font-weight:500; border:1px solid #e2e8f0;">
                     Réservations
                    @if($reservations > 0)
                        <span style="margin-left:auto; background:#f59e0b; color:#fff; font-size:0.7rem; font-weight:700; padding:2px 7px; border-radius:20px;">
                            {{ $reservations }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Statut rapide --}}
        <div style="background:#0f1f3d; border-radius:12px; padding:20px;">
            <div style="font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#475569; margin-bottom:14px;">
                État du stock
            </div>
            @php $tauxDispo = $totalLivres > 0 ? round(($livresDispo/$totalLivres)*100) : 0; @endphp
            <div style="margin-bottom:12px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span style="font-size:0.8rem; color:#94a3b8;">Disponibilité</span>
                    <span style="font-size:0.8rem; font-weight:700; color:#fff;">{{ $tauxDispo }}%</span>
                </div>
                <div style="background:#1e3358; border-radius:4px; height:6px;">
                    <div style="background:#2563eb; height:6px; border-radius:4px; width:{{ $tauxDispo }}%;"></div>
                </div>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:0.8rem;">
                <span style="color:#94a3b8;">{{ $livresDispo }} disponibles</span>
                <span style="color:#64748b;">{{ $totalLivres - $livresDispo }} empruntés</span>
            </div>
        </div>

    </div>
</div>

@endif

{{-- ════════════════════════════════════════════ --}}
{{-- VUE LECTEUR                                 --}}
{{-- ════════════════════════════════════════════ --}}
@if(auth()->user()->isLecteur())

@php
    $empruntsEnCours = \App\Models\Loan::with('book')
        ->where('user_id', auth()->id())
        ->whereNull('returned_at')
        ->orderBy('due_at')->get();

    $reservationsActives = \App\Models\Reservation::with('book')
        ->where('user_id', auth()->id())
        ->where('status', 'en_attente')->get();

    $enRetard = $empruntsEnCours->filter(fn($l) => $l->due_at->isPast())->count();
@endphp

<div style="margin-bottom:28px;">
    <h1 style="font-size:1.6rem; font-weight:700; color:#0f172a; margin:0 0 4px;">
        Bonjour, {{ auth()->user()->name }} 👋
    </h1>
    <p style="color:#64748b; font-size:0.9rem; margin:0;">
        Voici l'état de vos emprunts.
    </p>
</div>

{{-- Stats lecteur --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px;">
    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid #2563eb;">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">Emprunts en cours</div>
        <div style="font-size:2rem; font-weight:700; color:#0f172a;">{{ $empruntsEnCours->count() }}</div>
    </div>
    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid #f59e0b;">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">Réservations</div>
        <div style="font-size:2rem; font-weight:700; color:#0f172a;">{{ $reservationsActives->count() }}</div>
    </div>
    <div style="background:#fff; border-radius:12px; padding:20px; border:1px solid #e2e8f0; border-top:3px solid {{ $enRetard > 0 ? '#ef4444' : '#e2e8f0' }};">
        <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:8px;">En retard</div>
        <div style="font-size:2rem; font-weight:700; color:{{ $enRetard > 0 ? '#ef4444' : '#94a3b8' }};">{{ $enRetard }}</div>
    </div>
</div>

{{-- Emprunts --}}
<div style="display:grid; grid-template-columns:1fr 280px; gap:20px;">
    <div style="background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9;">
            <div style="font-size:0.95rem; font-weight:600; color:#0f172a;">Mes emprunts en cours</div>
        </div>
        @forelse($empruntsEnCours as $loan)
            @php $retard = $loan->due_at->isPast(); @endphp
            <div style="padding:14px 20px; border-bottom:1px solid #f8fafc; display:flex; align-items:center; justify-content:space-between; background:{{ $retard ? '#fff5f5' : '#fff' }};">
                <div>
                    <div style="font-size:0.875rem; font-weight:600; color:#0f172a;">{{ $loan->book->title }}</div>
                    <div style="font-size:0.78rem; color:#94a3b8; margin-top:2px;">{{ $loan->book->author }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.8rem; font-weight:600; color:{{ $retard ? '#ef4444' : '#475569' }};">
                        {{ $loan->due_at->format('d/m/Y') }}
                    </div>
                    @if($retard)
                        <span style="font-size:0.7rem; background:#fef2f2; color:#ef4444; padding:2px 7px; border-radius:20px; font-weight:600;">
                            Retard
                        </span>
                    @else
                        <span style="font-size:0.7rem; background:#f0fdf4; color:#16a34a; padding:2px 7px; border-radius:20px; font-weight:600;">
                            En cours
                        </span>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding:40px; text-align:center; color:#94a3b8; font-size:0.875rem;">
                Aucun emprunt en cours.
            </div>
        @endforelse
    </div>

    <div style="display:flex; flex-direction:column; gap:12px;">
        <a href="{{ route('books.index') }}"
           style="display:flex; align-items:center; gap:12px; padding:16px; border-radius:12px; background:#2563eb; color:#fff; text-decoration:none; font-weight:600; font-size:0.875rem;">
            <span style="font-size:1.5rem;">📚</span>
            <div>
                <div>Parcourir le catalogue</div>
                <div style="font-size:0.75rem; opacity:.75; font-weight:400;">Trouver un livre</div>
            </div>
        </a>
        <a href="{{ route('lecteur.espace') }}"
           style="display:flex; align-items:center; gap:12px; padding:16px; border-radius:12px; background:#fff; color:#374151; text-decoration:none; font-weight:600; font-size:0.875rem; border:1px solid #e2e8f0;">
            <span style="font-size:1.5rem;">👤</span>
            <div>
                <div>Mon espace complet</div>
                <div style="font-size:0.75rem; color:#94a3b8; font-weight:400;">Historique & réservations</div>
            </div>
        </a>
    </div>
</div>

@endif

@endsection