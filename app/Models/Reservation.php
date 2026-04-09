<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'reserved_at',
        'status',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
    ];

    // Une réservation appartient à un livre
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Une réservation appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope : réservations en attente
    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }
}
