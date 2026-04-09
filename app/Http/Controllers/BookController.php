<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Liste tous les livres avec recherche et filtres
    public function index(Request $request)
    {
        $query = Book::query();

        // Filtre par recherche texte (titre, auteur, catégorie)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre disponibilité
        if ($request->filled('disponible')) {
            $query->available();
        }

        $books = $query->orderBy('title')->paginate(12);

        // Liste des catégories pour le filtre
        $categories = Book::select('category')
                          ->distinct()
                          ->orderBy('category')
                          ->pluck('category');

        return view('books.index', compact('books', 'categories'));
    }

    // Formulaire de création
    public function create()
    {
        $this->authorize('create', Book::class);
        $categories = Book::select('category')->distinct()->pluck('category');
        return view('books.create', compact('categories'));
    }

    // Enregistrer un nouveau livre
    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'author'        => 'required|string|max:255',
            'isbn'          => 'nullable|string|unique:books,isbn',
            'category'      => 'required|string|max:100',
            'total_copies'  => 'required|integer|min:1',
            'cover_image'   => 'nullable|image|max:2048',
        ]);

        // available_copies = total_copies à la création
        $validated['available_copies'] = $validated['total_copies'];

        // Gestion de l'image de couverture
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                                               ->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')
                         ->with('success', 'Livre ajouté avec succès.');
    }

    // Fiche détaillée d'un livre
    public function show(Book $book)
    {
        // Réservation active de l'utilisateur connecté pour ce livre
        $maReservation = null;
        if (auth()->check()) {
            $maReservation = $book->reservations()
                                  ->where('user_id', auth()->id())
                                  ->where('status', 'en_attente')
                                  ->first();
        }

        // Position dans la file d'attente
        $positionFile = $book->reservations()
                             ->where('status', 'en_attente')
                             ->count();

        return view('books.show', compact('book', 'maReservation', 'positionFile'));
    }

    // Formulaire de modification
    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        $categories = Book::select('category')->distinct()->pluck('category');
        return view('books.edit', compact('book', 'categories'));
    }

    // Enregistrer les modifications
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'isbn'         => 'nullable|string|unique:books,isbn,' . $book->id,
            'category'     => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
            'cover_image'  => 'nullable|image|max:2048',
        ]);

        // Recalcule available_copies si total_copies change
        $diff = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $diff);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                                               ->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.show', $book)
                         ->with('success', 'Livre modifié avec succès.');
    }

    // Supprimer un livre
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        return redirect()->route('books.index')
                         ->with('success', 'Livre supprimé.');
    }
}
