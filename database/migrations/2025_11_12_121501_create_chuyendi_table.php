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
        Schema::create('Chuyendi', function (Blueprint $table) {
            $table->string('machuyendi', 15)->primary();
            $table->string('tenchuyen', 100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('maxe', 5)->nullable();
            $table->integer('SLgheconlai')->nullable();
            $table->dateTime('thoigiandi')->nullable();
            $table->integer('thoigiandichuyen')->nullable();
            $table->integer('gia')->nullable();
            
            $table->foreign('maxe')->references('maxe')->on('Xe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Chuyendi');
    }
};
