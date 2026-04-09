<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    // Tout le monde connecté peut voir la liste des livres
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Tout le monde connecté peut voir un livre
    public function view(User $user, Book $book): bool
    {
        return true;
    }

    // Seuls admin et bibliothécaire peuvent créer un livre
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'bibliothecaire']);
    }

    // Seuls admin et bibliothécaire peuvent modifier un livre
    public function update(User $user, Book $book): bool
    {
        return in_array($user->role, ['admin', 'bibliothecaire']);
    }

    // Seul l'admin peut supprimer un livre
    public function delete(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }
}
