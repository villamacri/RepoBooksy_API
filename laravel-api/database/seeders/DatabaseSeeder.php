<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Meetup;
use App\Models\MeetupAttendance;
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
            'last_name' => 'BookMeet', // Añadido: Obligatorio según tu migración
            'email' => 'admin@bookmeet.com',
            'password' => Hash::make('password123'),
            'role' => 'admin', // En inglés, según tu nuevo enum
            'registration_date' => Carbon::now(), // Añadido: Obligatorio según tu migración
            'status' => 'active', // En inglés, según tu nuevo enum
        ]);

        $user1 = User::create([
            'name' => 'Ana',
            'last_name' => 'Ruiz', // Añadido
            'email' => 'ana@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'registration_date' => Carbon::now(), // Añadido
            'status' => 'active',
        ]);

        $user2 = User::create([
            'name' => 'Carlos',
            'last_name' => 'Mateo', // Añadido
            'email' => 'carlos@ejemplo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'registration_date' => Carbon::now(), // Añadido
            'status' => 'active',
        ]);

        // 2. CREAR CATEGORÍAS (Géneros Literarios)
        $catFiccion = Category::create(['name' => 'Ficción Contemporánea', 'description' => 'Novelas actuales y realistas.']);
        $catFantasia = Category::create(['name' => 'Fantasía y Sci-Fi', 'description' => 'Mundos mágicos y futuros distópicos.']);
        $catMisterio = Category::create(['name' => 'Misterio y Thriller', 'description' => 'Novela negra y suspense.']);

        // 3. CREAR LIBROS DE SEGUNDA MANO
        $book1 = Book::create([
            'title' => 'El Imperio Final (Nacidos de la Bruma 1)',
            'author' => 'Brandon Sanderson',
            'publisher' => 'Nova',
            'publication_year' => 2016,
            'description' => 'Lo leí una vez, está impecable. Perfecto para empezar con el Cosmere.',
            'physical_condition' => 'like_new', // Opciones: new, like_new, good, acceptable, poor
            'operation_type' => 'sale', // Opciones: sale, exchange, both
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
            'price' => null, // Es intercambio
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

        // 4. CREAR EVENTOS (Quedadas en Locales Asociados)
        $meetup1 = Meetup::create([
            'name' => 'Club de Lectura: Thriller Español',
            'description' => 'Nos reunimos para comentar los últimos éxitos de la novela negra española y tomar un buen café.',
            'meetup_date' => Carbon::now()->addDays(7), // Dentro de una semana
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

        // 5. REGISTRAR ASISTENCIAS A LOS EVENTOS
        MeetupAttendance::create([
            'enrollment_date' => Carbon::now(),
            'status' => 'confirmed', // Opciones: confirmed, cancelled, waitlisted
            'user_id' => $user1->id,
            'meetup_id' => $meetup1->id,
        ]);

        MeetupAttendance::create([
            'enrollment_date' => Carbon::now(),
            'status' => 'confirmed',
            'user_id' => $user2->id,
            'meetup_id' => $meetup1->id,
        ]);
    }
}