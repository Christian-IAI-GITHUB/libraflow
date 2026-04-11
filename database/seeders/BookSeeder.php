<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title'           => 'Le Petit Prince',
                'author'          => 'Antoine de Saint-Exupéry',
                'isbn'            => '978-2-07-040850-4',
                'category'        => 'Roman',
                'total_copies'    => 3,
                'available_copies'=> 3,
            ],
            [
                'title'           => 'L\'Alchimiste',
                'author'          => 'Paulo Coelho',
                'isbn'            => '978-2-290-04946-3',
                'category'        => 'Roman',
                'total_copies'    => 2,
                'available_copies'=> 2,
            ],
            [
                'title'           => 'Introduction aux algorithmes',
                'author'          => 'Thomas H. Cormen',
                'isbn'            => '978-0-262-03384-8',
                'category'        => 'Informatique',
                'total_copies'    => 2,
                'available_copies'=> 2,
            ],
            [
                'title'           => 'Clean Code',
                'author'          => 'Robert C. Martin',
                'isbn'            => '978-0-13-235088-4',
                'category'        => 'Informatique',
                'total_copies'    => 1,
                'available_copies'=> 1,
            ],
            [
                'title'           => 'Mathématiques pour l\'informatique',
                'author'          => 'Bernard Kolman',
                'isbn'            => '978-2-744-07350-5',
                'category'        => 'Mathématiques',
                'total_copies'    => 4,
                'available_copies'=> 4,
            ],
            [
                'title'           => 'L\'Art de la guerre',
                'author'          => 'Sun Tzu',
                'isbn'            => '978-2-07-036976-1',
                'category'        => 'Philosophie',
                'total_copies'    => 2,
                'available_copies'=> 2,
            ],
            [
                'title'           => 'Sapiens',
                'author'          => 'Yuval Noah Harari',
                'isbn'            => '978-2-226-25723-1',
                'category'        => 'Histoire',
                'total_copies'    => 3,
                'available_copies'=> 3,
            ],
            [
                'title'           => 'Laravel : le guide complet',
                'author'          => 'Mohamed Younes',
                'isbn'            => null,
                'category'        => 'Informatique',
                'total_copies'    => 2,
                'available_copies'=> 2,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
