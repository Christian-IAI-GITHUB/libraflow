<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    // Admin et bibliothécaire voient toutes les réservations
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliothecaire']);
    }

    // Un lecteur voit seulement ses propres réservations
    public function view(User $user, Reservation $reservation): bool
    {
        if ($user->isLecteur()) {
            return $reservation->user_id === $user->id;
        }
        return true;
    }

    // N'importe quel lecteur connecté peut réserver
    public function create(User $user): bool
    {
        return true;
    }

    // Un lecteur peut annuler SA réservation, admin/biblio peuvent tout annuler
    public function update(User $user, Reservation $reservation): bool
    {
        if ($user->isLecteur()) {
            return $reservation->user_id === $user->id;
        }
        return true;
    }

    // Seul l'admin et le bibliothécaire peuvent supprimer une réservation
    public function delete(User $user, Reservation $reservation): bool
    {
        return in_array($user->role, ['admin', 'bibliothecaire']);
    }
}
