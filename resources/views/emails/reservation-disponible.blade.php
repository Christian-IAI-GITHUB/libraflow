@component('mail::message')
# Bonne nouvelle, {{ $user->name }} !

Le livre que vous attendiez est maintenant disponible :

**{{ $book->title }}** — *{{ $book->author }}*

Vous avez **48 heures** pour venir l'emprunter avant que votre réservation soit annulée.

@component('mail::button', ['url' => route('books.show', $book)])
Voir le livre
@endcomponent

À bientôt,
**L'équipe LibraFlow**
@endcomponent
