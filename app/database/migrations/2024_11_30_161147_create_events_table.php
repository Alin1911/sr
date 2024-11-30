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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id'); // `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('title', 256)->nullable(); // `title` varchar(256) DEFAULT NULL
            $table->text('description')->nullable(); // `description` text
            $table->string('image_url', 256)->nullable(); // `image_url` varchar(256) DEFAULT NULL
            $table->unsignedTinyInteger('category_id')->default(1); // `category_id` tinyint(2) UNSIGNED DEFAULT '1'

            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
