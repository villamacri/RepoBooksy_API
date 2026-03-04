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
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        MeetupAttendance::truncate();
        Transaction::truncate();
        Book::truncate();
        Meetup::truncate();
        Category::truncate();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $defaultPassword = Hash::make('password123');

        $admin = User::create([
            'name' => 'Admin',
            'last_name' => 'Booksy',
            'email' => 'admin@booksy.app',
            'password' => $defaultPassword,
            'phone' => '600000001',
            'role' => 'admin',
            'reputation' => 5.0,
            'registration_date' => Carbon::now()->subMonths(10),
            'status' => 'active',
        ]);

        $moderator = User::create([
            'name' => 'Marta',
            'last_name' => 'Soto',
            'email' => 'marta@booksy.app',
            'password' => $defaultPassword,
            'phone' => '600000002',
            'role' => 'moderator',
            'reputation' => 4.9,
            'access_level' => 'content_moderation',
            'responsibility_area' => 'Books and meetups moderation',
            'registration_date' => Carbon::now()->subMonths(8),
            'status' => 'active',
        ]);

        $userAna = User::create([
            'name' => 'Ana',
            'last_name' => 'Ruiz',
            'email' => 'ana@booksy.app',
            'password' => $defaultPassword,
            'phone' => '600000003',
            'role' => 'user',
            'reputation' => 4.8,
            'category_preferences' => json_encode(['Fantasy', 'Mystery']),
            'registration_date' => Carbon::now()->subMonths(6),
            'status' => 'active',
        ]);

        $userCarlos = User::create([
            'name' => 'Carlos',
            'last_name' => 'Mateo',
            'email' => 'carlos@booksy.app',
            'password' => $defaultPassword,
            'phone' => '600000004',
            'role' => 'user',
            'reputation' => 4.3,
            'category_preferences' => json_encode(['Classics', 'Science']),
            'registration_date' => Carbon::now()->subMonths(5),
            'status' => 'active',
        ]);

        $userLucia = User::create([
            'name' => 'Lucía',
            'last_name' => 'Paredes',
            'email' => 'lucia@booksy.app',
            'password' => $defaultPassword,
            'phone' => '600000005',
            'role' => 'user',
            'reputation' => 4.6,
            'category_preferences' => json_encode(['Non-fiction', 'Modern fiction']),
            'registration_date' => Carbon::now()->subMonths(4),
            'status' => 'active',
        ]);

        $userDavid = User::create([
            'name' => 'David',
            'last_name' => 'León',
            'email' => 'david@booksy.app',
            'password' => $defaultPassword,
            'phone' => '600000006',
            'role' => 'user',
            'reputation' => 3.9,
            'category_preferences' => json_encode(['Sci-Fi', 'Fantasy']),
            'registration_date' => Carbon::now()->subMonths(3),
            'status' => 'active',
        ]);

        $catModernFiction = Category::create([
            'name' => 'Modern Fiction',
            'description' => 'Contemporary novels and everyday stories.',
        ]);
        $catFantasy = Category::create([
            'name' => 'Fantasy & Sci-Fi',
            'description' => 'Epic worlds, space operas and speculative fiction.',
        ]);
        $catMystery = Category::create([
            'name' => 'Mystery & Thriller',
            'description' => 'Crime novels, suspense and detective stories.',
        ]);
        $catClassics = Category::create([
            'name' => 'Classics',
            'description' => 'Timeless books from international literature.',
        ]);
        $catScience = Category::create([
            'name' => 'Science',
            'description' => 'Popular science and educational reads.',
        ]);
        $catNonFiction = Category::create([
            'name' => 'Non-fiction',
            'description' => 'Essays, biographies and practical books.',
        ]);

        $bookMistborn = Book::create([
            'title' => 'Mistborn: The Final Empire',
            'author' => 'Brandon Sanderson',
            'publisher' => 'Tor Books',
            'publication_year' => 2006,
            'description' => 'Great entry point to modern fantasy. Very good condition.',
            'physical_condition' => 'like_new',
            'operation_type' => 'sale',
            'price' => 12.50,
            'publication_date' => Carbon::now()->subDays(12),
            'is_available' => true,
            'category_id' => $catFantasy->id,
            'user_id' => $userAna->id,
        ]);

        $book1984 = Book::create([
            'title' => '1984',
            'author' => 'George Orwell',
            'publisher' => 'Debolsillo',
            'publication_year' => 2013,
            'description' => 'Pocket edition with minor marks. Looking for exchange only.',
            'physical_condition' => 'good',
            'operation_type' => 'exchange',
            'price' => null,
            'publication_date' => Carbon::now()->subDays(18),
            'is_available' => true,
            'category_id' => $catClassics->id,
            'user_id' => $userCarlos->id,
        ]);

        $bookReinaRoja = Book::create([
            'title' => 'Reina Roja',
            'author' => 'Juan Gómez-Jurado',
            'publisher' => 'Ediciones B',
            'publication_year' => 2018,
            'description' => 'Hardcover copy, almost new.',
            'physical_condition' => 'new',
            'operation_type' => 'both',
            'price' => 15.00,
            'publication_date' => Carbon::now()->subDays(3),
            'is_available' => true,
            'category_id' => $catMystery->id,
            'user_id' => $userAna->id,
        ]);

        $bookSapiens = Book::create([
            'title' => 'Sapiens',
            'author' => 'Yuval Noah Harari',
            'publisher' => 'Debate',
            'publication_year' => 2015,
            'description' => 'Annotated copy, pages in good condition.',
            'physical_condition' => 'good',
            'operation_type' => 'sale',
            'price' => 14.90,
            'publication_date' => Carbon::now()->subDays(30),
            'is_available' => false,
            'category_id' => $catNonFiction->id,
            'user_id' => $userLucia->id,
        ]);

        $bookDune = Book::create([
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'publisher' => 'Ace',
            'publication_year' => 2010,
            'description' => 'Classic sci-fi copy for exchange lovers.',
            'physical_condition' => 'acceptable',
            'operation_type' => 'exchange',
            'price' => null,
            'publication_date' => Carbon::now()->subDays(26),
            'is_available' => false,
            'category_id' => $catFantasy->id,
            'user_id' => $userDavid->id,
        ]);

        $bookHobbit = Book::create([
            'title' => 'The Hobbit',
            'author' => 'J.R.R. Tolkien',
            'publisher' => 'Minotauro',
            'publication_year' => 2001,
            'description' => 'Used but clean copy, ideal for collectors.',
            'physical_condition' => 'good',
            'operation_type' => 'sale',
            'price' => 9.90,
            'publication_date' => Carbon::now()->subDays(9),
            'is_available' => true,
            'category_id' => $catFantasy->id,
            'user_id' => $userCarlos->id,
        ]);

        $bookCleanCode = Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'publication_year' => 2008,
            'description' => 'Highlighted in a few chapters. Perfect for students.',
            'physical_condition' => 'acceptable',
            'operation_type' => 'sale',
            'price' => 17.50,
            'publication_date' => Carbon::now()->subDays(7),
            'is_available' => true,
            'category_id' => $catScience->id,
            'user_id' => $userLucia->id,
        ]);

        $bookAtomicHabits = Book::create([
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'publisher' => 'Avery',
            'publication_year' => 2018,
            'description' => 'Almost pristine copy. Sale or exchange.',
            'physical_condition' => 'like_new',
            'operation_type' => 'both',
            'price' => 13.90,
            'publication_date' => Carbon::now()->subDays(4),
            'is_available' => true,
            'category_id' => $catNonFiction->id,
            'user_id' => $userDavid->id,
        ]);

        $meetupThriller = Meetup::create([
            'name' => 'Club de Lectura Sevilla',
            'description' => 'Weekly meetup to discuss modern thriller books.',
            'meetup_date' => Carbon::now()->addDays(7),
            'city' => 'Sevilla',
            'location' => 'Central Library, Meeting Room B',
            'max_capacity' => 12,
        ]);

        $meetupFantasy = Meetup::create([
            'name' => 'Intercambio de Fantasía',
            'description' => 'Bring one fantasy book and exchange it with another reader.',
            'meetup_date' => Carbon::now()->addDays(14),
            'city' => 'Sevilla',
            'location' => 'Cafe Quill & Coffee',
            'max_capacity' => 18,
        ]);

        $meetupNonFiction = Meetup::create([
            'name' => 'Encuentro de No Ficción y Ciencia',
            'description' => 'Open conversation around science and personal growth books.',
            'meetup_date' => Carbon::now()->addDays(21),
            'city' => 'Sevilla',
            'location' => 'Coworking Nova, Hall 1',
            'max_capacity' => 20,
        ]);

        $meetupThriller->users()->attach([$userAna->id, $userCarlos->id]);
        $meetupFantasy->users()->attach([$userDavid->id]);
        $meetupNonFiction->users()->attach([$moderator->id, $userLucia->id]);

        MeetupAttendance::create([
            'enrollment_date' => Carbon::now()->subDays(2),
            'status' => 'confirmed',
            'user_id' => $userAna->id,
            'meetup_id' => $meetupThriller->id,
        ]);
        MeetupAttendance::create([
            'enrollment_date' => Carbon::now()->subDays(2),
            'status' => 'confirmed',
            'user_id' => $userCarlos->id,
            'meetup_id' => $meetupThriller->id,
        ]);
        MeetupAttendance::create([
            'enrollment_date' => Carbon::now()->subDay(),
            'status' => 'waitlisted',
            'user_id' => $userDavid->id,
            'meetup_id' => $meetupFantasy->id,
        ]);
        MeetupAttendance::create([
            'enrollment_date' => Carbon::now()->subDay(),
            'status' => 'cancelled',
            'user_id' => $userLucia->id,
            'meetup_id' => $meetupFantasy->id,
        ]);
        MeetupAttendance::create([
            'enrollment_date' => Carbon::now(),
            'status' => 'confirmed',
            'user_id' => $moderator->id,
            'meetup_id' => $meetupNonFiction->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'transaction_date' => Carbon::now()->subDays(4),
            'amount' => 14.90,
            'payment_method' => 'bizum',
            'status' => 'completed',
            'book_id' => $bookSapiens->id,
            'buyer_id' => $userAna->id,
            'seller_id' => $userLucia->id,
        ]);

        Transaction::create([
            'transaction_type' => 'exchange',
            'transaction_date' => Carbon::now()->subDays(3),
            'amount' => null,
            'payment_method' => null,
            'status' => 'completed',
            'book_id' => $bookDune->id,
            'buyer_id' => $userCarlos->id,
            'seller_id' => $userDavid->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'transaction_date' => Carbon::now()->subDay(),
            'amount' => 15.00,
            'payment_method' => 'cash',
            'status' => 'pending',
            'book_id' => $bookReinaRoja->id,
            'buyer_id' => $userLucia->id,
            'seller_id' => $userAna->id,
        ]);

        Transaction::create([
            'transaction_type' => 'exchange',
            'transaction_date' => Carbon::now(),
            'amount' => null,
            'payment_method' => null,
            'status' => 'in_progress',
            'book_id' => $book1984->id,
            'buyer_id' => $userDavid->id,
            'seller_id' => $userCarlos->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'transaction_date' => Carbon::now()->subDays(2),
            'amount' => 9.90,
            'payment_method' => 'card',
            'status' => 'cancelled',
            'book_id' => $bookHobbit->id,
            'buyer_id' => $admin->id,
            'seller_id' => $userCarlos->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'transaction_date' => Carbon::now()->subHours(20),
            'amount' => 13.90,
            'payment_method' => 'bizum',
            'status' => 'in_progress',
            'book_id' => $bookAtomicHabits->id,
            'buyer_id' => $userAna->id,
            'seller_id' => $userDavid->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'transaction_date' => Carbon::now()->subHours(6),
            'amount' => 12.50,
            'payment_method' => 'cash',
            'status' => 'pending',
            'book_id' => $bookMistborn->id,
            'buyer_id' => $userCarlos->id,
            'seller_id' => $userAna->id,
        ]);

        Transaction::create([
            'transaction_type' => 'sale',
            'transaction_date' => Carbon::now()->subHours(3),
            'amount' => 17.50,
            'payment_method' => 'card',
            'status' => 'pending',
            'book_id' => $bookCleanCode->id,
            'buyer_id' => $userDavid->id,
            'seller_id' => $userLucia->id,
        ]);
    }
}