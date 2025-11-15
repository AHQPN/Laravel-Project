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
        Schema::create('Ve', function (Blueprint $table) {
            $table->string('mave', 10)->primary();
            $table->string('machuyendi', 15)->nullable();
            $table->string('maghe', 10)->nullable();
            
            $table->foreign('machuyendi')->references('machuyendi')->on('Chuyendi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Ve');
    }
};
