<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;

// Page d'accueil → redirige vers la connexion
Route::get('/', function () {
    return redirect()->route('login');
});

// Breeze dashboard minimal
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes accessibles à tous les utilisateurs connectés
Route::middleware(['auth'])->group(function () {

    // Breeze profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');
    Route::put('/settings/{key}', [SettingController::class, 'update'])
        ->name('settings.update');
});

// Routes d'authentification générées par Breeze
require __DIR__.'/auth.php';
