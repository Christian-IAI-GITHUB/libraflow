<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use App\Observers\LoanObserver;
use App\Policies\BookPolicy;
use App\Policies\LoanPolicy;
use App\Policies\ReservationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends AuthServiceProvider
{
    // Déclaration explicite des policies
    protected $policies = [
        Book::class         => BookPolicy::class,
        Loan::class         => LoanPolicy::class,
        Reservation::class  => ReservationPolicy::class,
    ];

    public function boot(): void
    {
        // Observer de la Phase 2
        Loan::observe(LoanObserver::class);

        // Gate spéciale : l'admin peut TOUT faire sans vérification
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
