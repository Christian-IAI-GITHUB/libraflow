<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // Liste des réservations (bibliothécaire/admin)
    public function index()
    {
        $this->authorize('viewAny', Reservation::class);

        $reservations = Reservation::with(['book', 'user'])
                                   ->enAttente()
                                   ->orderBy('reserved_at')
                                   ->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    // Créer une réservation
    public function store(Request $request)
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);
        $user = Auth::user();

        // Vérifie que le livre est bien indisponible
        // (on réserve seulement si pas d'exemplaire libre)
        if ($book->available_copies > 0) {
            return back()->withErrors([
                'book_id' => 'Ce livre est disponible, empruntez-le directement !'
            ]);
        }

        // Vérifie que l'utilisateur n'a pas déjà une réservation active
        $dejaReserve = Reservation::where('book_id', $book->id)
                                  ->where('user_id', $user->id)
                                  ->where('status', 'en_attente')
                                  ->exists();

        if ($dejaReserve) {
            return back()->withErrors([
                'book_id' => 'Vous avez déjà une réservation en attente pour ce livre.'
            ]);
        }

        // Position dans la file
        $position = Reservation::where('book_id', $book->id)
                                ->where('status', 'en_attente')
                                ->count() + 1;

        Reservation::create([
            'book_id'     => $book->id,
            'user_id'     => $user->id,
            'reserved_at' => Carbon::now(),
            'status'      => 'en_attente',
        ]);

        return back()->with('success',
            "Réservation enregistrée. Vous êtes {$position}e dans la file d'attente."
        );
    }

    // Annuler une réservation
    public function destroy(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update(['status' => 'annulee']);

        return back()->with('success', 'Réservation annulée.');
    }
}
