<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Wyłączanie sprawdzania kluczy obcych na czas migracji
        DB::statement('PRAGMA foreign_keys = OFF;');

        // Zmień nazwę tabeli, aby utworzyć jej backup
        DB::statement('ALTER TABLE events RENAME TO events_backup');

        // Tworzymy nową tabelę z nowym CHECK constraint
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // Nazwa wydarzenia
            $table->text('description')->nullable(); // Opis wydarzenia
            $table->string('location'); // Lokalizacja wydarzenia
            $table->dateTime('event_date'); // Data i czas wydarzenia
            $table->string('image_path')->nullable(); // Ścieżka do przechowywania obrazu
            $table->unsignedBigInteger('organizer_id');
            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('cascade');
            $table->enum('status', ['waiting', 'approved', 'rejected', 'expired', 'cancelled'])->default('waiting');
        });

        // Kopiujemy dane ze starej tabeli do nowej, zachowując wszystkie wartości
        DB::statement('INSERT INTO events (id, name, description, location, event_date, image_path, organizer_id, status, created_at, updated_at)
                       SELECT id, name, description, location, event_date, image_path, organizer_id, status, created_at, updated_at
                       FROM events_backup');

        // Usuwamy backup
        Schema::drop('events_backup');

        // Usuwamy i ponownie dodajemy klucz obcy w tabeli `sectors`
        Schema::table('sectors', function (Blueprint $table) {
            // Usuwamy istniejący klucz obcy
            $table->dropForeign(['event_id']);

            // Dodajemy klucz obcy wskazujący na nową tabelę `events`
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        // Włączanie sprawdzania kluczy obcych ponownie
        DB::statement('PRAGMA foreign_keys = ON;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Przywracamy oryginalną strukturę tabeli

        // Wyłączanie sprawdzania kluczy obcych
        DB::statement('PRAGMA foreign_keys = OFF;');

        DB::statement('ALTER TABLE events RENAME TO events_backup');

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->dateTime('event_date');
            $table->string('image_path')->nullable();
            $table->unsignedBigInteger('organizer_id');
            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('cascade');
            $table->enum('status', ['waiting', 'approved', 'rejected', 'expired'])->default('waiting'); // Bez wartości 'cancelled'
        });

        DB::statement('INSERT INTO events (id, name, description, location, event_date, image_path, organizer_id, status, created_at, updated_at)
                       SELECT id, name, description, location, event_date, image_path, organizer_id, status, created_at, updated_at
                       FROM events_backup');

        Schema::drop('events_backup');

        // Usuwamy i ponownie dodajemy klucz obcy w tabeli `sectors`
        Schema::table('sectors', function (Blueprint $table) {
            // Usuwamy istniejący klucz obcy
            $table->dropForeign(['event_id']);

            // Dodajemy klucz obcy wskazujący na nową tabelę `events`
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });

        // Włączanie sprawdzania kluczy obcych
        DB::statement('PRAGMA foreign_keys = ON;');
    }
};
