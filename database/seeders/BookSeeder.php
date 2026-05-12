<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Format;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BookSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Book::truncate();
        Format::truncate();
        Schema::enableForeignKeyConstraints();

        $defaultSynopsis = "Entre la vida y la muerte hay una biblioteca. Cada libro ofrece la oportunidad de probar otra vida que podrías haber vivido. Una obra maestra que explora la profundidad de la experiencia humana.";

        $books = [
            ['title' => 'La insoportable levedad del ser', 'author' => 'Milan Kundera', 'category' => 'Filosofía', 'pages' => 320, 'price' => 22.50, 'image_url' => 'img/LaInsoportableLevedadDelSer.png', 'is_bestseller' => false],
            ['title' => 'Don Quijote de la Mancha', 'author' => 'Miguel de Cervantes', 'category' => 'Novela', 'pages' => 863, 'price' => 24.99, 'image_url' => 'img/DonQuijoteDeLaMancha.jpg', 'is_bestseller' => false],
            ['title' => 'Cien años de soledad', 'author' => 'Gabriel García Márquez', 'category' => 'Novela', 'pages' => 417, 'price' => 19.99, 'image_url' => 'img/CienAñosdeSoledad.png', 'is_bestseller' => false],
            ['title' => '1984', 'author' => 'George Orwell', 'category' => 'Distopía', 'pages' => 328, 'price' => 16.99, 'image_url' => 'img/1984.png', 'is_bestseller' => false],
            ['title' => 'Orgullo y prejuicio', 'author' => 'Jane Austen', 'category' => 'Romántica', 'pages' => 279, 'price' => 18.99, 'image_url' => 'img/OrgulloYPrejuicio.png', 'is_bestseller' => false],
            ['title' => 'La Odisea', 'author' => 'Homero', 'category' => 'Épica', 'pages' => 541, 'price' => 21.99, 'image_url' => 'img/Odisea.png', 'is_bestseller' => false],
            ['title' => 'El señor de los anillos I', 'author' => 'J.R.R. Tolkien', 'category' => 'Fantasía', 'pages' => 1178, 'price' => 34.99, 'image_url' => 'img/ElSeñorDeLosAnillos1.png', 'is_bestseller' => false],
            ['title' => 'Fahrenheit 451', 'author' => 'Ray Bradbury', 'category' => 'Distopía', 'pages' => 249, 'price' => 15.99, 'image_url' => 'img/Fahrenheit451.png', 'is_bestseller' => false],
            ['title' => 'La sombra del viento', 'author' => 'Carlos Ruiz Zafón', 'category' => 'Novela', 'pages' => 565, 'price' => 22.99, 'image_url' => 'img/LaSombraDelViento.png', 'is_bestseller' => false],
            ['title' => 'El principito', 'author' => 'Antoine de Saint-Exupéry', 'category' => 'Fábula', 'pages' => 96, 'price' => 12.99, 'image_url' => 'img/ElPrincipito.png', 'is_bestseller' => false],
            ['title' => 'Crimen y castigo', 'author' => 'Fiódor Dostoyevski', 'category' => 'Novela', 'pages' => 671, 'price' => 25.99, 'image_url' => 'img/CrimenYCastigo.png', 'is_bestseller' => false],
            ['title' => 'Matar a un ruiseñor', 'author' => 'Harper Lee', 'category' => 'Novela', 'pages' => 336, 'price' => 19.99, 'image_url' => 'img/MatarAUnRuiseñor.png', 'is_bestseller' => false],
        ];

        foreach ($books as $data) {
            $basePrice = $data['price'];
            unset($data['price']);

            $book = Book::create(array_merge([
                'synopsis' => $defaultSynopsis,
                'published_date' => 'Ene 2026',
                'rating' => 4.5,
                'publisher' => 'Penguin Books',
                'language' => 'Español'
            ], $data));

            $book->formats()->createMany([
                ['type' => 'Tapa dura', 'price' => $basePrice, 'stock' => 0],
                ['type' => 'E-book', 'price' => round($basePrice * 0.6, 2), 'stock' => 0],
                ['type' => 'Audiolibro', 'price' => round($basePrice * 0.8, 2), 'stock' => 0],
            ]);
        }
    }
}
