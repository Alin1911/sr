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
            $table->bigIncrements('id'); // `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->unsignedBigInteger('performanceId')->nullable(); // `performanceId` bigint(20) UNSIGNED DEFAULT NULL
            $table->unsignedInteger('transactionId'); // `transactionId` int(11) UNSIGNED NOT NULL

            $table->timestamps(); // Adaugă `created_at` și `updated_at` dacă este nevoie
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
