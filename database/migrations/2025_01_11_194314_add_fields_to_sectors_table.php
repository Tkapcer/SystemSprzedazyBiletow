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
        Schema::table('sectors', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->after('id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('name')->after('id');
            $table->integer('seats')->after('name');
            $table->decimal('price', 8, 2)->after('seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn(['event_id', 'name', 'seats', 'price']);
        });
    }
};
