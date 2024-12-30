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
        Schema::create('transaction', function (Blueprint $table) {
            $table->bigIncrements('id'); // `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->unsignedInteger('user_id')->default(0); // `user_id` int(11) UNSIGNED DEFAULT '0'

            $table->timestamps(); // Adaugă `created_at` și `updated_at`, dacă sunt necesare
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
