<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Meetup;
use App\Models\MeetupAttendance;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CREAR USUARIOS DE PRUEBA
        $admin = User::create([
            'name' => 'Admin',
            'last_name' => 'BookMeet',
            'email' => 'admin@bookmeet.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'registration_date' => Carbon::now(),
            'status' => 'active',
        ]);

        $user1 = User::create([
            'name' => 'Ana',
            'last_name' => 'Ruiz',
            'email' => 'ana@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'registration_date' => Carbon::now(),
            'status' => 'active',
        ]);

        $user2 = User::create([
            'name' => 'Carlos',
            'last_name' => 'Mateo',
            'email' => 'carlos@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'registration_date' => Carbon::now(),
            'status' => 'active',
        ]);

        $user3 = User::create([
            'name' => 'Laura',
            'last_name' => 'Gómez',
            'email' => 'laura@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'registration_date' => Carbon::now()->subMonths(2),
            'status' => 'active',
        ]);

        $user4 = User::create([
            'name' => 'David',
            'last_name' => 'Sánchez',
            'email' => 'david@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'registration_date' => Carbon::now()->subWeeks(3),
            'status' => 'active',
        ]);

        // 2. CREAR CATEGORÍAS (Géneros Literarios)
        $catFiccion = Category::create(['name' => 'Ficción Contemporánea', 'description' => 'Novelas actuales y realistas.']);
        $catFantasia = Category::create(['name' => 'Fantasía y Sci-Fi', 'description' => 'Mundos mágicos y futuros distópicos.']);
        $catMisterio = Category::create(['name' => 'Misterio y Thriller', 'description' => 'Novela negra y suspense.']);
        $catRomance = Category::create(['name' => 'Romance', 'description' => 'Historias de amor, pasión y desamor.']);
        $catEnsayo = Category::create(['name' => 'Ensayo y Divulgación', 'description' => 'Ciencia, historia y aprendizaje.']);
        $catTerror = Category::create(['name' => 'Terror', 'description' => 'Libros para leer con la luz encendida.']);

        // 3. CREAR LIBROS DE SEGUNDA MANO
        $book1 = Book::create([
            'title' => 'El Imperio Final (Nacidos de la Bruma 1)',
            'author' => 'Brandon Sanderson',
            'publisher' => 'Nova',
            'publication_year' => 2016,
            'description' => 'Lo leí una vez, está impecable. Perfecto para empezar con el Cosmere.',
            'physical_condition' => 'like_new',
            'operation_type' => 'sale',
            'price' => 12.50,
            'publication_date' => Carbon::now()->subDays(2),
            'is_available' => true,
            'category_id' => $catFantasia->id,
            'user_id' => $user1->id,
        ]);

        $book2 = Book::create([
            'title' => '1984',
            'author' => 'George Orwell',
            'publisher' => 'Debolsillo',
            'publication_year' => 2013,
            'description' => 'Edición de bolsillo. Tiene las esquinas un poco dobladas pero se lee perfectamente. Busco cambiarlo por algo de Asimov.',
            'physical_condition' => 'good',
            'operation_type' => 'exchange',
            'price' => null,
            'publication_date' => Carbon::now()->subDays(5),
            'is_available' => true,
            'category_id' => $catFiccion->id,
            'user_id' => $user2->id,
        ]);

        $book3 = Book::create([
            'title' => 'Reina Roja',
            'author' => 'Juan Gómez-Jurado',
            'publisher' => 'Ediciones B',
            'publication_year' => 2018,
            'description' => 'Tapa dura. Me lo regalaron y ya lo había leído.',
            'physical_condition' => 'new',
            'operation_type' => 'both',
            'price' => 15.00,
            'publication_date' => Carbon::now()->subHours(5),
            'is_available' => true,
            'category_id' => $catMisterio->id,
            'user_id' => $user1->id,
        ]);

        $book4 = Book::create([
            'title' => 'Orgullo y Prejuicio',
            'author' => 'Jane Austen',
            'publisher' => 'Alianza Editorial',
            'publication_year' => 2014,
            'description' => 'Clásico imprescindible. Lo tengo repetido y busco cambiarlo.',
            'physical_condition' => 'like_new',
            'operation_type' => 'exchange',
            'price' => null,
            'publication_date' => Carbon::now()->subDays(1),
            'is_available' => true,
            'category_id' => $catRomance->id,
            'user_id' => $user3->id,
        ]);

        $book5 = Book::create([
            'title' => 'Sapiens: De animales a dioses',
            'author' => 'Yuval Noah Harari',
            'publisher' => 'Debate',
            'publication_year' => 2015,
            'description' => 'Edición ilustrada. Tiene algunas notas a lápiz en los márgenes.',
            'physical_condition' => 'acceptable',
            'operation_type' => 'sale',
            'price' => 18.00,
            'publication_date' => Carbon::now()->subDays(4),
            'is_available' => true,
            'category_id' => $catEnsayo->id,
            'user_id' => $user4->id,
        ]);

        $book6 = Book::create([
            'title' => 'El Resplandor',
            'author' => 'Stephen King',
            'publisher' => 'Plaza & Janés',
            'publication_year' => 2005,
            'description' => 'Un poco amarillento por el tiempo, pero sin páginas sueltas.',
            'physical_condition' => 'poor',
            'operation_type' => 'both',
            'price' => 5.00,
            'publication_date' => Carbon::now()->subWeeks(1),
            'is_available' => true,
            'category_id' => $catTerror->id,
            'user_id' => $user3->id,
        ]);
        
        $book7 = Book::create([
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'publisher' => 'Nova',
            'publication_year' => 2020,
            'description' => 'Totalmente nuevo, sin leer. Fue un regalo duplicado.',
            'physical_condition' => 'new',
            'operation_type' => 'sale',
            'price' => 20.00,
            'publication_date' => Carbon::now()->subHours(2),
            'is_available' => true,
            'category_id' => $catFantasia->id,
            'user_id' => $user4->id,
        ]);

        // 4. CREAR EVENTOS (Quedadas en Locales Asociados)
        $meetup1 = Meetup::create([
            'name' => 'Club de Lectura: Thriller Español',
            'description' => 'Nos reunimos para comentar los últimos éxitos de la novela negra española y tomar un buen café.',
            'meetup_date' => Carbon::now()->addDays(7),
            'location' => 'Cafetería La Cacharrería (Calle Regina, Sevilla)',
            'max_capacity' => 10,
        ]);

        $meetup2 = Meetup::create([
            'name' => 'Intercambio Masivo de Fantasía',
            'description' => 'Trae tus libros de fantasía y ciencia ficción para intercambiar. ¡Conoceremos a otros lectores de la zona!',
            'meetup_date' => Carbon::now()->addDays(14),
            'location' => 'Librería Rayuela (Calle José Luis Luque, Sevilla)',
            'max_capacity' => 20,
        ]);

        $meetup3 = Meetup::create([
            'name' => 'Tarde de Clásicos y Té',
            'description' => 'Debate sobre novela romántica del siglo XIX y merienda en grupo.',
            'meetup_date' => Carbon::now()->addDays(20),
            'location' => 'Tetería Al-Andalus (Sevilla)',
            'max_capacity' => 8,
        ]);

        // 5. REGISTRAR ASISTENCIAS A LOS EVENTOS
        MeetupAttendance::create([
            'enrollment_date' => Carbon::now(),
            'status' => 'confirmed',
            'user_id' => $user1->id,
            'meetup_id' => $meetup1->id,
        ]);

        MeetupAttendance::create([
            'enrollment_date' => Carbon::now(),
            'status' => 'confirmed',
            'user_id' => $user2->id,
            'meetup_id' => $meetup1->id,
        ]);

        MeetupAttendance::create([
            'enrollment_date' => Carbon::now(),
            'status' => 'confirmed',
            'user_id' => $user3->id,
            'meetup_id' => $meetup3->id,
        ]);

        // 6. CREAR TRANSACCIONES DE PRUEBA
        // Transacciones de venta completadas
        Transaction::create([
            'transaction_type' => 'sale',
            'status' => 'completed',
            'transaction_date' => Carbon::now()->subDays(10),
            'amount' => 12.50,
            'payment_method' => 'credit_card',
            'book_id' => $book1->id,
            'buyer_id' => $user2->id,
            'seller_id' => $user1->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'status' => 'completed',
            'transaction_date' => Carbon::now()->subDays(8),
            'amount' => 15.00,
            'payment_method' => 'paypal',
            'book_id' => $book3->id,
            'buyer_id' => $user2->id,
            'seller_id' => $user1->id,
        ]);

        // Transacciones de intercambio
        Transaction::create([
            'transaction_type' => 'exchange',
            'status' => 'completed',
            'transaction_date' => Carbon::now()->subDays(5),
            'amount' => null,
            'payment_method' => null,
            'book_id' => $book2->id,
            'buyer_id' => $user1->id,
            'seller_id' => $user2->id,
        ]);

        Transaction::create([
            'transaction_type' => 'exchange',
            'status' => 'pending',
            'transaction_date' => Carbon::now()->subDay(),
            'amount' => null,
            'payment_method' => null,
            'book_id' => $book1->id,
            'buyer_id' => $user2->id,
            'seller_id' => $admin->id,
        ]);

        // Generar más transacciones usando el factory (30 adicionales)
        $allUsers = [$admin, $user1, $user2, $user3, $user4];
        $allBooks = [$book1, $book2, $book3, $book4, $book5, $book6, $book7];

        for ($i = 0; $i < 30; $i++) {
            $randomBuyer = collect($allUsers)->random();
            $randomSeller = collect($allUsers)->where('id', '!=', $randomBuyer->id)->random();
            $randomBook = collect($allBooks)->random();
            $transactionType = fake()->randomElement(['sale', 'exchange']);

            Transaction::create([
                'transaction_type' => $transactionType,
                'status' => fake()->randomElement(['pending', 'completed', 'in_progress']),
                'transaction_date' => fake()->dateTimeBetween('-20 days', 'now'),
                'amount' => $transactionType === 'sale' ? fake()->randomFloat(2, 8, 45) : null,
                'payment_method' => $transactionType === 'sale' ? fake()->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']) : null,
                'book_id' => $randomBook->id,
                'buyer_id' => $randomBuyer->id,
                'seller_id' => $randomSeller->id,
            ]);
        }
    }
}