<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'penalty_amount',
    ];

    // Convertit automatiquement ces colonnes en objets Carbon
    protected $casts = [
        'borrowed_at'  => 'datetime',
        'due_at'       => 'datetime',
        'returned_at'  => 'datetime',
    ];

    // Un emprunt appartient à un livre
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Un emprunt appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope : emprunts en retard (date dépassée ET pas encore rendu)
    public function scopeOverdue($query)
    {
        return $query->where('due_at', '<', Carbon::now())
                     ->whereNull('returned_at');
    }

    // Scope : emprunts en cours (pas encore rendus)
    public function scopeActive($query)
    {
        return $query->whereNull('returned_at');
    }

    // Calcule la pénalité de retard automatiquement
    public function calculatePenalty(): float
    {
        // Si le livre est déjà rendu ou pas en retard, pas de pénalité
        if ($this->returned_at || Carbon::now()->lessThanOrEqualTo($this->due_at)) {
            return 0;
        }

        // Récupère le montant journalier depuis les settings
        $dailyRate = (float) Setting::getValue('penalite_journaliere', 100);

        // Nombre de jours de retard (toujours positif)
        $daysLate = (int) floor($this->due_at->diffInDays(Carbon::now()));

        return $daysLate * $dailyRate;
    }
}
