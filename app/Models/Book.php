<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category',
        'total_copies',
        'available_copies',
        'cover_image',
    ];

    // Un livre peut avoir plusieurs emprunts
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    // Un livre peut avoir plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Scope : livres disponibles (au moins 1 exemplaire libre)
    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    // Scope : recherche par titre, auteur ou catégorie
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
                     ->orWhere('author', 'like', "%{$search}%")
                     ->orWhere('category', 'like', "%{$search}%");
    }
}
