<?php

namespace App\Providers;

use App\Models\Loan;
use App\Observers\LoanObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // On dit à Laravel : "surveille le modèle Loan avec LoanObserver"
        Loan::observe(LoanObserver::class);
    }
}
