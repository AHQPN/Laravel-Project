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
        Schema::create('lotrinh', function (Blueprint $table) {
            $table->string('machuyendi', 15);
            $table->string('matinh', 4);
            $table->integer('trinhtu')->nullable();
            
            $table->primary(['machuyendi', 'matinh']);
            $table->foreign('machuyendi')->references('machuyendi')->on('chuyendi');
            $table->foreign('matinh')->references('matinh')->on('tinhthanh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotrinh');
    }
};
