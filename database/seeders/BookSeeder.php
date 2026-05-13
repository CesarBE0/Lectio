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

        $books = [
            ['title' => 'La insoportable levedad del ser', 'author' => 'Milan Kundera', 'category' => 'Filosofía', 'pages' => 320, 'price' => 22.50, 'image_url' => 'img/LaInsoportableLevedadDelSer.png', 'is_bestseller' => false, 'synopsis' => 'En medio de la turbulenta Primavera de Praga, cuatro personajes entrelazan sus vidas en una red de amor, celos, sexo y traición. Una obra maestra que explora la dicotomía entre el peso de nuestras decisiones y la ligereza de una vida sin ataduras. ¿Es preferible el compromiso absoluto o la libertad total?'],
            ['title' => 'Don Quijote de la Mancha', 'author' => 'Miguel de Cervantes', 'category' => 'Novela', 'pages' => 863, 'price' => 24.99, 'image_url' => 'img/DonQuijoteDeLaMancha.jpg', 'is_bestseller' => false, 'synopsis' => 'Enloquecido por la lectura obsesiva de novelas de caballerías, el hidalgo Alonso Quijano decide armarse caballero y salir en busca de aventuras. Acompañado por su pragmático y fiel escudero, Sancho Panza, cabalgará por La Mancha enfrentándose a molinos que parecen gigantes en la novela que fundó la literatura moderna.'],
            ['title' => 'Cien años de soledad', 'author' => 'Gabriel García Márquez', 'category' => 'Novela', 'pages' => 417, 'price' => 19.99, 'image_url' => 'img/CienAñosdeSoledad.png', 'is_bestseller' => false, 'synopsis' => 'Viaja al mítico pueblo de Macondo para conocer la épica historia de la familia Buendía a lo largo de siete generaciones. Milagros cotidianos, pasiones prohibidas, guerras inútiles y un destino ineludible se mezclan en la cumbre del realismo mágico. Un viaje fascinante a las raíces de la memoria y la soledad.'],
            ['title' => '1984', 'author' => 'George Orwell', 'category' => 'Distopía', 'pages' => 328, 'price' => 16.99, 'image_url' => 'img/1984.png', 'is_bestseller' => false, 'synopsis' => 'En un futuro aterrador y distópico, Oceanía está dominada por el omnipresente Gran Hermano, que vigila cada movimiento y reescribe la historia a su antojo. Winston Smith, un empleado del Ministerio de la Verdad, decide rebelarse cometiendo el mayor de los crímenes: pensar por sí mismo y enamorarse.'],
            ['title' => 'Orgullo y prejuicio', 'author' => 'Jane Austen', 'category' => 'Romántica', 'pages' => 279, 'price' => 18.99, 'image_url' => 'img/OrgulloYPrejuicio.png', 'is_bestseller' => false, 'synopsis' => 'En la Inglaterra del siglo XIX, la independiente e ingeniosa Elizabeth Bennet conoce al rico y altivo Sr. Darcy. Lo que comienza como una relación llena de malentendidos, frialdad y juicios precipitados, se transformará poco a poco en uno de los romances más inolvidables y afilados de la literatura universal.'],
            ['title' => 'La Odisea', 'author' => 'Homero', 'category' => 'Épica', 'pages' => 541, 'price' => 21.99, 'image_url' => 'img/Odisea.png', 'is_bestseller' => false, 'synopsis' => 'Tras diez años luchando en la Guerra de Troya, el astuto héroe Odiseo (Ulises) emprende el largo y accidentado viaje de regreso a su hogar en Ítaca. Dioses furiosos, monstruos mitológicos, sirenas y hechiceras se interpondrán en su camino mientras su esposa, Penélope, lo espera rodeada de pretendientes.'],
            ['title' => 'El señor de los anillos I', 'author' => 'J.R.R. Tolkien', 'category' => 'Fantasía', 'pages' => 1178, 'price' => 34.99, 'image_url' => 'img/ElSeñorDeLosAnillos1.png', 'is_bestseller' => false, 'synopsis' => 'En la tranquila Comarca, el joven hobbit Frodo Bolsón recibe un encargo que cambiará el destino de la Tierra Media: proteger el Anillo Único, un artefacto de poder oscuro y corruptor. Junto a una variopinta compañía de elfos, enanos, hombres y magos, emprenderá un viaje desesperado para destruirlo.'],
            ['title' => 'Fahrenheit 451', 'author' => 'Ray Bradbury', 'category' => 'Distopía', 'pages' => 249, 'price' => 15.99, 'image_url' => 'img/Fahrenheit451.png', 'is_bestseller' => false, 'synopsis' => 'Guy Montag es un bombero, pero en su mundo la misión de los bomberos no es apagar incendios, sino provocarlos para quemar libros. En una sociedad que ha prohibido la lectura para garantizar la "felicidad" ignorante de los ciudadanos, Montag comenzará a cuestionarse el sistema tras un encuentro revelador.'],
            ['title' => 'La sombra del viento', 'author' => 'Carlos Ruiz Zafón', 'category' => 'Novela', 'pages' => 565, 'price' => 22.99, 'image_url' => 'img/LaSombraDelViento.png', 'is_bestseller' => false, 'synopsis' => 'En la brumosa Barcelona de 1945, un padre lleva a su hijo al misterioso Cementerio de los Libros Olvidados. Allí, el joven Daniel Sempere descubre un libro maldito que cambiará el rumbo de su vida y lo arrastrará a un laberinto de intrigas, secretos oscuros y amores trágicos escondidos en el alma de la ciudad.'],
            ['title' => 'El principito', 'author' => 'Antoine de Saint-Exupéry', 'category' => 'Fábula', 'pages' => 96, 'price' => 12.99, 'image_url' => 'img/ElPrincipito.png', 'is_bestseller' => false, 'synopsis' => 'Un aviador perdido en el desierto del Sahara se encuentra con un pequeño príncipe proveniente de un asteroide lejano. A través de las inocentes pero profundas preguntas del niño, descubrirá lecciones universales sobre la amistad, el amor, la pérdida y la verdadera esencia de la vida, invisible a los ojos.'],
            ['title' => 'Crimen y castigo', 'author' => 'Fiódor Dostoyevski', 'category' => 'Novela', 'pages' => 671, 'price' => 25.99, 'image_url' => 'img/CrimenYCastigo.png', 'is_bestseller' => false, 'synopsis' => 'Rodión Raskólnikov, un brillante pero empobrecido estudiante en San Petersburgo, comete un asesinato brutal convencido de que su superioridad intelectual lo exime de las leyes morales. Sin embargo, el verdadero castigo no vendrá de la policía, sino de la aplastante culpa y el tormento psicológico que devorarán su alma.'],
            ['title' => 'Matar a un ruiseñor', 'author' => 'Harper Lee', 'category' => 'Novela', 'pages' => 336, 'price' => 19.99, 'image_url' => 'img/MatarAUnRuiseñor.png', 'is_bestseller' => false, 'synopsis' => 'A través de los ojos de la pequeña Scout Finch, viajamos al sur de Estados Unidos durante la Gran Depresión. Su padre, el íntegro abogado Atticus Finch, decide defender a un hombre negro acusado injustamente de un terrible crimen, enfrentándose a los arraigados prejuicios y al racismo de toda una comunidad.'],
        ];

        foreach ($books as $data) {
            $basePrice = $data['price'];
            unset($data['price']);

            $book = Book::create(array_merge([
                'published_date' => 'Ene 2026',
                'rating' => 4.5,
                'publisher' => 'Lectio',
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
