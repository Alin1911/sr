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
        Schema::create('hall', function (Blueprint $table) {
            $table->bigIncrements('id'); // `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('name', 100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci'); // `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
            $table->unsignedInteger('city_id')->nullable(); // `city_id` int(10) UNSIGNED DEFAULT NULL
            $table->string('address', 256)->nullable(); // `address` varchar(256) DEFAULT NULL

            $table->timestamps(); // Adaugă coloanele `created_at` și `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hall');
    }
};
