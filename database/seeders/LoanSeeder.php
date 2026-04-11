<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $lecteur = User::where('role', 'lecteur')->first();
        $biblio  = User::where('role', 'bibliothecaire')->first();

        // Emprunt en cours — dans les délais
        $book1 = Book::where('title', 'Le Petit Prince')->first();
        Loan::create([
            'book_id'     => $book1->id,
            'user_id'     => $lecteur->id,
            'borrowed_at' => Carbon::now()->subDays(3),
            'due_at'      => Carbon::now()->addDays(11),
        ]);
        // L'Observer décrémente, on force la synchro
        $book1->decrement('available_copies');

        // Emprunt en retard (pour tester la page retards)
        $book2 = Book::where('title', 'Clean Code')->first();
        Loan::create([
            'book_id'          => $book2->id,
            'user_id'          => $lecteur->id,
            'borrowed_at'      => Carbon::now()->subDays(20),
            'due_at'           => Carbon::now()->subDays(6),
            'penalty_amount'   => 0, // sera calculée dynamiquement
        ]);
        $book2->decrement('available_copies');

        // Emprunt rendu avec pénalité (historique)
        $book3 = Book::where('title', "L'Alchimiste")->first();
        Loan::create([
            'book_id'        => $book3->id,
            'user_id'        => $lecteur->id,
            'borrowed_at'    => Carbon::now()->subDays(30),
            'due_at'         => Carbon::now()->subDays(16),
            'returned_at'    => Carbon::now()->subDays(10),
            'penalty_amount' => 600, // 6 jours × 100 FCFA
        ]);

        // Réservation en attente
        // D'abord on emprunte tous les exemplaires de Sapiens
        $book4 = Book::where('title', 'Sapiens')->first();
        for ($i = 0; $i < $book4->total_copies; $i++) {
            Loan::create([
                'book_id'     => $book4->id,
                'user_id'     => $biblio->id,
                'borrowed_at' => Carbon::now()->subDays(5),
                'due_at'      => Carbon::now()->addDays(9),
            ]);
            $book4->decrement('available_copies');
        }
        // Puis on crée la réservation
        Reservation::create([
            'book_id'     => $book4->id,
            'user_id'     => $lecteur->id,
            'reserved_at' => Carbon::now()->subDay(),
            'status'      => 'en_attente',
        ]);
    }
}
