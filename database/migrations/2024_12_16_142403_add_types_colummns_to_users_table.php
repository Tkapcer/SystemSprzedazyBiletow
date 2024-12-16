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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->enum('type', ['user', 'admin', 'organizer'])->default('user');
            $table->enum('organizerStatus', ['confirmed', 'waiting', 'rejected'])->default('waiting');
            $table->string('companyName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->dropColumn('type');
            $table->dropColumn('organizerStatus');
            $table->dropColumn('companyName');
        });
    }
};
