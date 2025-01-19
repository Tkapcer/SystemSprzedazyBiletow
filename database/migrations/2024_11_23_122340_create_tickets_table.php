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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status', ['reserved', 'purchased'])->default('reserved');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('sector_id');
            $table->foreign('sector_id')->references('id')->on('sectors');
            $table->string('code');
            $table->integer('number_of_seats')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
            $table->dropColumn('code');
            $table->dropColumn('number_of_seats');
        });
    }
};
