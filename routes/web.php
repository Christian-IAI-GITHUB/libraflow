<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;

// Page d'accueil → redirige vers le catalogue
Route::get('/', function () {
    return redirect()->route('books.index');
});

// Routes accessibles à tous les utilisateurs connectés
Route::middleware(['auth'])->group(function () {

    // Catalogue des livres (lecture pour tous)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // Réservations (le lecteur peut créer/annuler les siennes)
    Route::resource('reservations', ReservationController::class)
         ->only(['index', 'store', 'destroy']);

    // Historique et profil du lecteur
    Route::get('/mon-espace', [LoanController::class, 'monEspace'])->name('lecteur.espace');
});

// Routes réservées à admin + bibliothécaire
Route::middleware(['auth', 'role:admin,bibliothecaire'])->group(function () {

    // Gestion complète du catalogue
    Route::resource('books', BookController::class)
         ->except(['index', 'show']);

    // Gestion des emprunts et retours
    Route::resource('loans', LoanController::class);
    Route::patch('/loans/{loan}/retour', [LoanController::class, 'retour'])
         ->name('loans.retour');

    // Vue des retards
    Route::get('/retards', [LoanController::class, 'retards'])->name('loans.retards');
});

// Routes réservées à l'admin uniquement
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('settings', SettingController::class)
         ->only(['index', 'update']);
});

// Routes d'authentification générées par Breeze
require __DIR__.'/auth.php';
