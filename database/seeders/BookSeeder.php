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
            [
                'title' => 'La insoportable levedad del ser', 'author' => 'Milan Kundera', 'category' => 'Filosofía', 'pages' => 320, 'price' => 22.50, 'is_bestseller' => false,
                'image_url' => 'img/La_insoportable_levedad_del_ser.png',
                'synopsis' => 'En medio de la turbulenta Primavera de Praga, cuatro personajes entrelazan sus vidas en una red de amor, celos, sexo y traición. Una obra maestra que explora la dicotomía entre el peso de nuestras decisiones y la ligereza de una vida sin ataduras. ¿Es preferible el compromiso absoluto o la libertad total?',
                'pdf_path' => 'La_insoportable_levedad_del_ser.pdf', 'audio_path' => 'La_insoportable_levedad_del_ser.mp3'
            ],
            [
                'title' => 'Don Quijote de la Mancha', 'author' => 'Miguel de Cervantes', 'category' => 'Novela', 'pages' => 863, 'price' => 24.99, 'is_bestseller' => false,
                'image_url' => 'img/Don_quijote_de_la_mancha.jpg',
                'synopsis' => 'Enloquecido por la lectura obsesiva de novelas de caballerías, el hidalgo Alonso Quijano decide armarse caballero y salir en busca de aventuras. Acompañado por su pragmático y fiel escudero, Sancho Panza, cabalgará por La Mancha enfrentándose a molinos que parecen gigantes en la novela que fundó la literatura moderna.',
                'pdf_path' => 'Don_quijote_de_la_mancha.pdf', 'audio_path' => 'Don_quijote_de_la_mancha.mp3'
            ],
            [
                'title' => 'Cien años de soledad', 'author' => 'Gabriel García Márquez', 'category' => 'Novela', 'pages' => 417, 'price' => 19.99, 'is_bestseller' => false,
                'image_url' => 'img/Cien_anos_de_soledad.png',
                'synopsis' => 'Viaja al mítico pueblo de Macondo para conocer la épica historia de la familia Buendía a lo largo de siete generaciones. Milagros cotidianos, pasiones prohibidas, guerras inútiles y un destino ineludible se mezclan en la cumbre del realismo mágico. Un viaje fascinante a las raíces de la memoria y la soledad.',
                'pdf_path' => 'Cien_anos_de_soledad.pdf', 'audio_path' => 'Cien_anos_de_soledad.mp3'
            ],
            [
                'title' => '1984', 'author' => 'George Orwell', 'category' => 'Distopía', 'pages' => 328, 'price' => 16.99, 'is_bestseller' => false,
                'image_url' => 'img/1984.png',
                'synopsis' => 'En un futuro aterrador y distópico, Oceanía está dominada por el omnipresente Gran Hermano, que vigila cada movimiento y reescribe la historia a su antojo. Winston Smith, un empleado del Ministerio de la Verdad, decide rebelarse cometiendo el mayor de los crímenes: pensar por sí mismo y enamorarse.',
                'pdf_path' => '1984.pdf', 'audio_path' => '1984.mp3'
            ],
            [
                'title' => 'Orgullo y prejuicio', 'author' => 'Jane Austen', 'category' => 'Romántica', 'pages' => 279, 'price' => 18.99, 'is_bestseller' => false,
                'image_url' => 'img/Orgullo_y_prejuicio.png',
                'synopsis' => 'En la Inglaterra del siglo XIX, la independiente e ingeniosa Elizabeth Bennet conoce al rico y altivo Sr. Darcy. Lo que comienza como una relación llena de malentendidos, frialdad y juicios precipitados, se transformará poco a poco en uno de los romances más inolvidables y afilados de la literatura universal.',
                'pdf_path' => 'Orgullo_y_prejuicio.pdf', 'audio_path' => 'Orgullo_y_prejuicio.mp3'
            ],
            [
                'title' => 'La Odisea', 'author' => 'Homero', 'category' => 'Épica', 'pages' => 541, 'price' => 21.99, 'is_bestseller' => false,
                'image_url' => 'img/La_odisea.png',
                'synopsis' => 'Tras diez años luchando en la Guerra de Troya, el astuto héroe Odiseo (Ulises) emprende el largo y accidentado viaje de regreso a su hogar en Ítaca. Dioses furiosos, monstruos mitológicos, sirenas y hechiceras se interpondrán en su camino mientras su esposa, Penélope, lo espera rodeada de pretendientes.',
                'pdf_path' => 'La_odisea.pdf', 'audio_path' => 'La_odisea.mp3'
            ],
            [
                'title' => 'El señor de los anillos I', 'author' => 'J.R.R. Tolkien', 'category' => 'Fantasía', 'pages' => 1178, 'price' => 34.99, 'is_bestseller' => false,
                'image_url' => 'img/El_senor_de_los_anillos_i.png',
                'synopsis' => 'En la tranquila Comarca, el joven hobbit Frodo Bolsón recibe un encargo que cambiará el destino de la Tierra Media: proteger el Anillo Único, un artefacto de poder oscuro y corruptor. Junto a una variopinta compañía de elfos, enanos, hombres y magos, emprenderá un viaje desesperado para destruirlo.',
                'pdf_path' => 'El_senor_de_los_anillos_i.pdf', 'audio_path' => 'El_senor_de_los_anillos_i.mp3'
            ],
            [
                'title' => 'Fahrenheit 451', 'author' => 'Ray Bradbury', 'category' => 'Distopía', 'pages' => 249, 'price' => 15.99, 'is_bestseller' => false,
                'image_url' => 'img/Fahrenheit_451.png',
                'synopsis' => 'Guy Montag es un bombero, pero en su mundo la misión de los bomberos no es apagar incendios, sino provocarlos para quemar libros. En una sociedad que ha prohibido la lectura para garantizar la "felicidad" ignorante de los ciudadanos, Montag comenzará a cuestionarse el sistema tras un encuentro revelador.',
                'pdf_path' => 'Fahrenheit_451.pdf', 'audio_path' => 'Fahrenheit_451.mp3'
            ],
            [
                'title' => 'La sombra del viento', 'author' => 'Carlos Ruiz Zafón', 'category' => 'Novela', 'pages' => 565, 'price' => 22.99, 'is_bestseller' => false,
                'image_url' => 'img/La_sombra_del_viento.png',
                'synopsis' => 'En la brumosa Barcelona de 1945, un padre lleva a su hijo al misterioso Cementerio de los Libros Olvidados. Allí, el joven Daniel Sempere descubre un libro maldito que cambiará el rumbo de su vida y lo arrastrará a un laberinto de intrigas, secretos oscuros y amores trágicos escondidos en el alma de la ciudad.',
                'pdf_path' => 'La_sombra_del_viento.pdf', 'audio_path' => 'La_sombra_del_viento.mp3'
            ],
            [
                'title' => 'El principito', 'author' => 'Antoine de Saint-Exupéry', 'category' => 'Fábula', 'pages' => 96, 'price' => 12.99, 'is_bestseller' => false,
                'image_url' => 'img/El_principito.png',
                'synopsis' => 'Un aviador perdido en el desierto del Sahara se encuentra con un pequeño príncipe proveniente de un asteroide lejano. A través de las inocentes pero profundas preguntas del niño, descubrirá lecciones universales sobre la amistad, el amor, la pérdida y la verdadera esencia de la vida, invisible a los ojos.',
                'pdf_path' => 'El_principito.pdf', 'audio_path' => 'El_principito.mp3'
            ],
            [
                'title' => 'Crimen y castigo', 'author' => 'Fiódor Dostoyevski', 'category' => 'Novela', 'pages' => 671, 'price' => 25.99, 'is_bestseller' => false,
                'image_url' => 'img/Crimen_y_castigo.png',
                'synopsis' => 'Rodión Raskólnikov, un brillante pero empobrecido estudiante en San Petersburgo, comete un asesinato brutal convencido de que su superioridad intelectual lo exime de las leyes morales. Sin embargo, el verdadero castigo no vendrá de la policía, sino de la aplastante culpa y el tormento psicológico que devorarán su alma.',
                'pdf_path' => 'Crimen_y_castigo.pdf', 'audio_path' => 'Crimen_y_castigo.mp3'
            ],
            [
                'title' => 'Matar a un ruiseñor', 'author' => 'Harper Lee', 'category' => 'Novela', 'pages' => 336, 'price' => 19.99, 'is_bestseller' => false,
                'image_url' => 'img/Matar_a_un_ruisenor.png',
                'synopsis' => 'A través de los ojos de la pequeña Scout Finch, viajamos al sur de Estados Unidos durante la Gran Depresión. Su padre, el íntegro abogado Atticus Finch, decide defender a un hombre negro acusado injustamente de un terrible crimen, enfrentándose a los arraigados prejuicios y al racismo de toda una comunidad.',
                'pdf_path' => 'Matar_a_un_ruisenor.pdf', 'audio_path' => 'Matar_a_un_ruisenor.mp3'
            ],

            // --- LOS 12 NUEVOS ---
            [
                'title' => 'El retrato de Dorian Gray', 'author' => 'Oscar Wilde', 'category' => 'Novela', 'pages' => 256, 'price' => 18.50, 'is_bestseller' => false,
                'image_url' => 'img/El_retrato_de_dorian_gray.png',
                'synopsis' => 'En la alta sociedad londinense, el joven y extraordinariamente hermoso Dorian Gray formula un deseo aterrador: que un retrato suyo envejezca y asuma las cicatrices de sus pecados mientras él mantiene su juventud intacta. Un oscuro y fascinante viaje hacia la vanidad, la moralidad y la corrupción del alma humana.',
                'pdf_path' => 'El_retrato_de_dorian_gray.pdf', 'audio_path' => 'El_retrato_de_dorian_gray.mp3'
            ],
            [
                'title' => 'Drácula', 'author' => 'Bram Stoker', 'category' => 'Terror', 'pages' => 418, 'price' => 19.50, 'is_bestseller' => false,
                'image_url' => 'img/Dracula.png',
                'synopsis' => 'El joven abogado Jonathan Harker viaja a los sombríos Cárpatos para cerrar un negocio con un misterioso noble, sin saber que está a punto de desatar un mal ancestral sobre Londres. Diarios, cartas y recortes de prensa tejen la escalofriante historia del vampiro más famoso de todos los tiempos y la desesperada caza para destruirlo.',
                'pdf_path' => 'Dracula.pdf', 'audio_path' => 'Dracula.mp3'
            ],
            [
                'title' => 'Un mundo feliz', 'author' => 'Aldous Huxley', 'category' => 'Distopía', 'pages' => 288, 'price' => 17.99, 'is_bestseller' => false,
                'image_url' => 'img/Un_mundo_feliz.png',
                'synopsis' => 'En una sociedad futurista donde el dolor y la tristeza han sido erradicados mediante ingeniería genética y el consumo obligatorio de la droga "soma", la humanidad ha sacrificado su libertad, el arte y el amor verdadero a cambio de una felicidad prefabricada. ¿Qué ocurre cuando alguien despierta de esta pesadilla perfecta?',
                'pdf_path' => 'Un_mundo_feliz.pdf', 'audio_path' => 'Un_mundo_feliz.mp3'
            ],
            [
                'title' => 'El conde de Montecristo', 'author' => 'Alexandre Dumas', 'category' => 'Aventuras', 'pages' => 1243, 'price' => 28.50, 'is_bestseller' => false,
                'image_url' => 'img/El_conde_de_montecristo.png',
                'synopsis' => 'Traicionado por sus amigos y condenado a pudrirse en la temible prisión del castillo de If, el joven marinero Edmundo Dantés descubre el secreto de un inmenso tesoro oculto. Tras una fuga espectacular, regresará a París años después bajo una nueva y poderosa identidad, con un único y gélido propósito: la venganza absoluta.',
                'pdf_path' => 'El_conde_de_montecristo.pdf', 'audio_path' => 'El_conde_de_montecristo.mp3'
            ],
            [
                'title' => 'Frankenstein o el moderno Prometeo', 'author' => 'Mary Shelley', 'category' => 'Terror', 'pages' => 280, 'price' => 16.90, 'is_bestseller' => false,
                'image_url' => 'img/Frankenstein.png',
                'synopsis' => 'Obsesionado con el secreto de la vida y la muerte, el científico Victor Frankenstein logra ensamblar y dar vida a una criatura a partir de restos humanos. Horrorizado por su propia creación, huye, desatando la tragedia. Una profunda reflexión sobre los límites de la ciencia, la soledad y la necesidad de ser amado.',
                'pdf_path' => 'Frankenstein.pdf', 'audio_path' => 'Frankenstein.mp3'
            ],
            [
                'title' => 'Ensayo sobre la ceguera', 'author' => 'José Saramago', 'category' => 'Novela', 'pages' => 328, 'price' => 21.50, 'is_bestseller' => false,
                'image_url' => 'img/Ensayo_sobre_la_ceguera.png',
                'synopsis' => 'Una repentina e inexplicable "ceguera blanca" comienza a expandirse por la ciudad como una epidemia incontrolable. A medida que las autoridades encierran a los infectados en cuarentenas brutales, la sociedad se desmorona rápidamente hacia el caos y el instinto de supervivencia. Solo una mujer, que ha fingido estar ciega, es testigo de la verdadera oscuridad humana.',
                'pdf_path' => 'Ensayo_sobre_la_ceguera.pdf', 'audio_path' => 'Ensayo_sobre_la_ceguera.mp3'
            ],
            [
                'title' => 'Pedro Páramo', 'author' => 'Juan Rulfo', 'category' => 'Novela', 'pages' => 132, 'price' => 15.50, 'is_bestseller' => false,
                'image_url' => 'img/Pedro_paramo.png',
                'synopsis' => '"Vine a Comala porque me dijeron que acá vivía mi padre, un tal Pedro Páramo". Así arranca este viaje hipnótico a un pueblo fantasma, asfixiado por el calor y los murmullos de almas en pena. Una de las cumbres de la literatura hispanoamericana que borra las fronteras entre los vivos y los muertos, la memoria y el polvo.',
                'pdf_path' => 'Pedro_paramo.pdf', 'audio_path' => 'Pedro_paramo.mp3'
            ],
            [
                'title' => 'El nombre de la rosa', 'author' => 'Umberto Eco', 'category' => 'Histórica', 'pages' => 784, 'price' => 22.90, 'is_bestseller' => false,
                'image_url' => 'img/El_nombre_de_la_rosa.png',
                'synopsis' => 'Invierno de 1327. En una abadía benedictina del norte de Italia, una serie de extraños y macabros asesinatos sacuden a los monjes. El astuto franciscano Guillermo de Baskerville y su joven discípulo Adso de Melk deberán descifrar códigos ocultos, laberintos prohibidos y disputas teológicas para desenmascarar al asesino antes de que vuelva a actuar.',
                'pdf_path' => 'El_nombre_de_la_rosa.pdf', 'audio_path' => 'El_nombre_de_la_rosa.mp3'
            ],
            [
                'title' => 'Rayuela', 'author' => 'Julio Cortázar', 'category' => 'Novela', 'pages' => 600, 'price' => 20.00, 'is_bestseller' => false,
                'image_url' => 'img/Rayuela.png',
                'synopsis' => 'La apasionada y caótica relación entre Horacio Oliveira y la Maga en las calles del París bohemio es solo el punto de partida de este rompecabezas literario. Un libro que es muchos libros a la vez, donde tú como lector decides el orden de los capítulos. Una experiencia revolucionaria sobre el amor, el jazz y la búsqueda existencial.',
                'pdf_path' => 'Rayuela.pdf', 'audio_path' => 'Rayuela.mp3'
            ],
            [
                'title' => 'Los miserables', 'author' => 'Victor Hugo', 'category' => 'Clásico', 'pages' => 1463, 'price' => 29.99, 'is_bestseller' => false,
                'image_url' => 'img/Los_miserables.png',
                'synopsis' => 'Jean Valjean, un exconvicto perseguido implacablemente por el inflexible inspector Javert durante décadas, busca redimirse cuidando de la huérfana Cosette. Su historia personal se entrelaza con las barricadas, la pobreza y las revueltas del París del siglo XIX, en una epopeya monumental sobre la justicia, la ley y la gracia.',
                'pdf_path' => 'Los_miserables.pdf', 'audio_path' => 'Los_miserables.mp3'
            ],
            [
                'title' => 'Rebelión en la granja', 'author' => 'George Orwell', 'category' => 'Sátira', 'pages' => 144, 'price' => 14.50, 'is_bestseller' => false,
                'image_url' => 'img/Rebelion_en_la_granja.png',
                'synopsis' => 'Cansados de los abusos de su amo, los animales de la Granja Manor deciden iniciar una revolución y tomar el control para crear una sociedad justa e igualitaria. Sin embargo, los cerdos, líderes del alzamiento, comienzan a corromperse por el poder. Una sátira mordaz y brillante sobre cómo los ideales políticos pueden retorcerse hasta convertirse en tiranía.',
                'pdf_path' => 'Rebelion_en_la_granja.pdf', 'audio_path' => 'Rebelion_en_la_granja.mp3'
            ],
            [
                'title' => 'Nada', 'author' => 'Carmen Laforet', 'category' => 'Novela', 'pages' => 304, 'price' => 18.50, 'is_bestseller' => false,
                'image_url' => 'img/Nada.png',
                'synopsis' => 'Andrea llega a la Barcelona de posguerra con una maleta llena de ilusiones para empezar sus estudios universitarios. Sin embargo, en la lúgubre y opresiva casa familiar de la calle Aribau, encontrará un ambiente asfixiante de miseria, secretos oscuros y violencia contenida. Un retrato crudo e inolvidable de la juventud intentando abrirse paso entre las sombras.',
                'pdf_path' => 'Nada.pdf', 'audio_path' => 'Nada.mp3'
            ],
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
