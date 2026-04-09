<?php

namespace App\Mail;

use App\Models\Book;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationDisponibleMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Book $book,
        public User $user
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre livre est disponible — ' . $this->book->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reservation-disponible',
        );
    }
}
