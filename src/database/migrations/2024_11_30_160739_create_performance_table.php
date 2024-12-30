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
        Schema::create('performance', function (Blueprint $table) {
            $table->bigIncrements('id'); // `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->unsignedBigInteger('event_id'); // `event_id` bigint(20) UNSIGNED NOT NULL
            $table->dateTime('starting_date'); // `starting_date` datetime NOT NULL
            $table->dateTime('ending_date'); // `ending_date` datetime NOT NULL
            $table->unsignedInteger('hall_id'); // `hall_id` int(11) UNSIGNED NOT NULL
            $table->unsignedInteger('promoter_id'); // `promoter_id` int(11) UNSIGNED NOT NULL

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance');
    }
};
