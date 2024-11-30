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
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id'); // `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('name', 64)->nullable()->charset('utf8'); // `name` varchar(64) CHARACTER SET utf8 DEFAULT NULL
            $table->string('judet', 32)->nullable()->charset('utf8')->collation('utf8_bin'); // `judet` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL

            $table->timestamps(); // Adaugă `created_at` și `updated_at`, dacă sunt necesare
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
