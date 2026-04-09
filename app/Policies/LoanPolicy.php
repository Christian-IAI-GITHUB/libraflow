<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    // Admin et bibliothécaire voient tous les emprunts
    // Le lecteur ne voit que les siens (géré dans le controller)
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Un lecteur peut voir son propre emprunt, le reste voit tout
    public function view(User $user, Loan $loan): bool
    {
        if ($user->isLecteur()) {
            return $loan->user_id === $user->id;
        }
        return true;
    }

    // Seuls admin et bibliothécaire créent un emprunt
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliothecaire']);
    }

    // Seuls admin et bibliothécaire enregistrent un retour
    public function update(User $user, Loan $loan): bool
    {
        return in_array($user->role, ['admin', 'bibliothecaire']);
    }

    // Seul l'admin peut supprimer un emprunt
    public function delete(User $user, Loan $loan): bool
    {
        return $user->role === 'admin';
    }
}
