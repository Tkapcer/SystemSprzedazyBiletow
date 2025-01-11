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
        Schema::table('events', function (Blueprint $table) {
            $table->string('name')->after('id'); // Nazwa wydarzenia
            $table->text('description')->nullable()->after('name'); // Opis wydarzenia
            $table->string('location')->after('description'); // Lokalizacja wydarzenia
            $table->dateTime('event_date')->after('location'); // Data i czas wydarzenia
            $table->string('image_path')->nullable()->after('event_date'); // Ścieżka do przechowywania obrazu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('description');
            $table->dropColumn('location');
            $table->dropColumn('event_date');
            $table->dropColumn('image_path');
        });
    }
};
