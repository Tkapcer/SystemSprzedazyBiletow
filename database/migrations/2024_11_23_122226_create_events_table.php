<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
            $table->enum('status', ['waiting', 'approved', 'rejected', 'expired'])->default('waiting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
