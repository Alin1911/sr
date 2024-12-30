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
        Schema::create('deleted_ticket', function (Blueprint $table) {
            $table->increments('id'); // `id` int(11) NOT NULL AUTO_INCREMENT
            $table->integer('performanceId'); // `performanceId` int(11) NOT NULL
            $table->integer('transactionId')->nullable(); // `transactionId` int(11) DEFAULT NULL

            $table->timestamps(); // Adaugă `created_at` și `updated_at`, dacă sunt necesare
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_ticket');
    }
};
