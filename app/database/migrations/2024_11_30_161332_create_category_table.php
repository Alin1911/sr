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
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id'); // `id` int(11) NOT NULL AUTO_INCREMENT
            $table->string('name', 16); // `name` varchar(16) NOT NULL

            // Adaugă coloane pentru timestamps, dacă este necesar:
            // $table->timestamps(); // `created_at` și `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
