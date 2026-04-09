<?php

namespace App\Observers;

use App\Models\Loan;

class LoanObserver
{
    // Appelé automatiquement APRÈS la création d'un emprunt
    public function created(Loan $loan): void
    {
        // On décrémente le nombre d'exemplaires disponibles
        $loan->book->decrement('available_copies');
    }

    // Appelé automatiquement APRÈS la mise à jour d'un emprunt
    public function updated(Loan $loan): void
    {
        // Si returned_at vient d'être rempli (livre rendu)
        // isDirty() vérifie si le champ a changé dans cette requête
        if ($loan->isDirty('returned_at') && $loan->returned_at !== null) {
            // On réincrémente le nombre d'exemplaires disponibles
            $loan->book->increment('available_copies');
        }
    }
}
