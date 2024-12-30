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
            // Adaugă coloanele noi după category_id
            $table->unsignedBigInteger('city_id_e')->nullable()->after('category_id');
            $table->unsignedBigInteger('category_id_e')->nullable()->after('city_id_e');
            $table->unsignedBigInteger('promoter_id_e')->nullable()->after('category_id_e');
            $table->unsignedBigInteger('hall_id_e')->nullable()->after('promoter_id_e');
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
