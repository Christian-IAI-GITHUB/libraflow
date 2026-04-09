<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Setting;
use App\Models\Reservation;
use App\Mail\ReservationDisponibleMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoanController extends Controller
{
    // Liste tous les emprunts (bibliothécaire/admin)
    public function index()
    {
        $this->authorize('viewAny', Loan::class);

        $loans = Loan::with(['book', 'user'])
                     ->active()
                     ->orderBy('due_at')
                     ->paginate(15);

        return view('loans.index', compact('loans'));
    }

    // Formulaire de création d'emprunt
    public function create()
    {
        $this->authorize('create', Loan::class);

        $books = Book::available()->orderBy('title')->get();
        $users = \App\Models\User::where('role', 'lecteur')
                                 ->orderBy('name')
                                 ->get();

        return view('loans.create', compact('books', 'users'));
    }

    // Enregistrer un nouvel emprunt
    public function store(Request $request)
    {
        $this->authorize('create', Loan::class);

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Vérifie qu'il reste des exemplaires disponibles
        if ($book->available_copies <= 0) {
            return back()->withErrors([
                'book_id' => 'Ce livre n\'a plus d\'exemplaires disponibles.'
            ]);
        }

        // Durée d'emprunt depuis les paramètres
        $duree = (int) Setting::getValue('duree_emprunt', 14);

        // Crée l'emprunt (l'Observer décrémente available_copies automatiquement)
        Loan::create([
            'book_id'     => $validated['book_id'],
            'user_id'     => $validated['user_id'],
            'borrowed_at' => Carbon::now(),
            'due_at'      => Carbon::now()->addDays($duree),
        ]);

        return redirect()->route('loans.index')
                         ->with('success', 'Emprunt enregistré. Retour prévu le '
                             . Carbon::now()->addDays($duree)->format('d/m/Y'));
    }

    // Enregistrer un retour
    public function retour(Loan $loan)
    {
        $this->authorize('update', $loan);

        if ($loan->returned_at) {
            return back()->withErrors(['error' => 'Ce livre a déjà été retourné.']);
        }

        // Calcule la pénalité avant le retour
        $penalite = $loan->calculatePenalty();

        // Enregistre le retour (l'Observer incrémente available_copies)
        $loan->update([
            'returned_at'    => Carbon::now(),
            'penalty_amount' => $penalite,
        ]);

        // Vérifie s'il y a une réservation en attente pour ce livre
        $prochaineListe = $loan->book->reservations()
                                     ->enAttente()
                                     ->orderBy('reserved_at')
                                     ->first();

        if ($prochaineListe) {
            // Notifie le premier en file d'attente
            $prochaineListe->update(['status' => 'notifie']);
            Mail::to($prochaineListe->user->email)
                ->send(new ReservationDisponibleMail($loan->book, $prochaineListe->user));
        }

        $message = $penalite > 0
            ? "Retour enregistré. Pénalité de retard : {$penalite} FCFA."
            : "Retour enregistré. Merci !";

        return redirect()->route('loans.index')->with('success', $message);
    }

    // Liste des emprunts en retard
    public function retards()
    {
        $loans = Loan::with(['book', 'user'])
                     ->overdue()
                     ->orderBy('due_at')
                     ->get()
                     ->map(function ($loan) {
                         // Calcule et attache la pénalité actuelle à chaque emprunt
                         $loan->penalite_actuelle = $loan->calculatePenalty();
                         return $loan;
                     });

        return view('loans.retards', compact('loans'));
    }

    // Espace personnel du lecteur
    public function monEspace()
    {
        $user = auth()->user();

        // Emprunts en cours
        $empruntsEnCours = Loan::with('book')
                               ->where('user_id', $user->id)
                               ->active()
                               ->orderBy('due_at')
                               ->get();

        // Historique complet
        $historique = Loan::with('book')
                          ->where('user_id', $user->id)
                          ->whereNotNull('returned_at')
                          ->orderByDesc('returned_at')
                          ->paginate(10);

        // Réservations en attente
        $reservations = Reservation::with('book')
                                   ->where('user_id', $user->id)
                                   ->where('status', 'en_attente')
                                   ->get();

        // Total des pénalités impayées
        $totalPenalites = Loan::where('user_id', $user->id)
                              ->where('penalty_amount', '>', 0)
                              ->whereNotNull('returned_at')
                              ->sum('penalty_amount');

        return view('loans.mon-espace', compact(
            'empruntsEnCours',
            'historique',
            'reservations',
            'totalPenalites'
        ));
    }

    // Afficher un emprunt
    public function show(Loan $loan)
    {
        $this->authorize('view', $loan);
        return view('loans.show', compact('loan'));
    }

    // Non utilisé mais requis par --resource
    public function edit(Loan $loan) {}
    public function update(Request $request, Loan $loan) {}
    public function destroy(Loan $loan) {}
}
